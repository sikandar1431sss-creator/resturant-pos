<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Check if current user is restricted to only their own created orders.
     */
    protected function isRestrictedCashier()
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();
        if ($user->level == 1 || $user->hasRole('admin') || $user->hasRole('kitchen') || $user->can('sales.view_all')) {
            return false;
        }

        return true;
    }

    /**
     * Display the Kitchen Display System (KDS) board.
     */
    public function index()
    {
        $setting = Setting::first();
        $isCashier = $this->isRestrictedCashier();
        $userId = $isCashier ? auth()->id() : null;

        $pendingQuery = Penjualan::where('kitchen_status', 'pending')
            ->where('total_item', '>', 0)
            ->whereDate('created_at', Carbon::today());

        $cookingQuery = Penjualan::where('kitchen_status', 'cooking')
            ->where('total_item', '>', 0)
            ->whereDate('created_at', Carbon::today());

        $readyQuery = Penjualan::where('kitchen_status', 'ready')
            ->where('total_item', '>', 0)
            ->whereDate('created_at', Carbon::today());

        $servedQuery = Penjualan::where('kitchen_status', 'served')
            ->where('total_item', '>', 0)
            ->whereDate('created_at', Carbon::today());

        if ($userId) {
            $pendingQuery->where('id_user', $userId);
            $cookingQuery->where('id_user', $userId);
            $readyQuery->where('id_user', $userId);
            $servedQuery->where('id_user', $userId);
        }

        $pendingCount = $pendingQuery->count();
        $cookingCount = $cookingQuery->count();
        $readyCount = $readyQuery->count();
        $servedTodayCount = $servedQuery->count();

        // Get list of active cashiers/waiters for admin/manager/kitchen filter
        $cashiersList = [];
        if (!$isCashier) {
            $cashiersList = User::orderBy('name')->get();
        }

        return view('kitchen.index', compact(
            'setting',
            'pendingCount',
            'cookingCount',
            'readyCount',
            'servedTodayCount',
            'isCashier',
            'cashiersList'
        ));
    }

    /**
     * Return live active kitchen orders JSON for auto-refresh and audio alerts.
     */
    public function data(Request $request)
    {
        $typeFilter = $request->get('type', 'all'); // all, Dine-In, Takeaway, Delivery
        $statusTab = $request->get('tab', 'active'); // active, ready, served
        $isCashier = $this->isRestrictedCashier();

        $query = Penjualan::with(['detail.produk.kategori', 'member', 'user'])
            ->where('total_item', '>', 0);

        // Multi-Cashier Scoping:
        // Cashiers see strictly their own placed orders.
        // Admin/Manager/Kitchen see all, with optional cashier_id filter.
        if ($isCashier) {
            $query->where('id_user', auth()->id());
        } elseif ($request->filled('cashier_id') && $request->cashier_id !== 'all') {
            $query->where('id_user', $request->cashier_id);
        }

        if ($statusTab === 'active') {
            $query->whereIn('kitchen_status', ['pending', 'cooking']);
        } elseif ($statusTab === 'ready') {
            $query->where('kitchen_status', 'ready');
        } elseif ($statusTab === 'served') {
            $query->where('kitchen_status', 'served')
                  ->whereDate('created_at', Carbon::today())
                  ->orderBy('kitchen_served_at', 'desc')
                  ->limit(30);
        } else {
            // all live
            $query->whereIn('kitchen_status', ['pending', 'cooking', 'ready']);
        }

        if ($typeFilter !== 'all') {
            $query->where('tipe_order', $typeFilter);
        }

        if ($statusTab !== 'served') {
            $query->orderBy('created_at', 'asc');
        }

        $orders = $query->get();

        $now = Carbon::now();
        $formattedOrders = $orders->map(function ($order) use ($now) {
            $createdAt = Carbon::parse($order->created_at);
            $elapsedSeconds = $createdAt->diffInSeconds($now);
            $elapsedMinutes = floor($elapsedSeconds / 60);

            // Urgency color level based on fast food standard preparation SLA
            // < 5 mins = Normal (Green), 5 - 10 mins = Warning (Yellow), > 10 mins = Urgent (Red)
            $urgency = 'normal';
            if ($elapsedMinutes >= 10) {
                $urgency = 'urgent';
            } elseif ($elapsedMinutes >= 5) {
                $urgency = 'warning';
            }

            $items = $order->detail->map(function ($d) {
                return [
                    'id_produk' => $d->id_produk,
                    'nama_produk' => optional($d->produk)->nama_produk ?? 'Menu Item',
                    'jumlah' => (int)$d->jumlah,
                    'kategori' => optional(optional($d->produk)->kategori)->nama_kategori ?? 'General',
                ];
            });

            return [
                'id_penjualan' => $order->id_penjualan,
                'invoice' => '#INV-' . tambah_nol_didepan($order->id_penjualan, 5),
                'token' => tambah_nol_didepan($order->id_penjualan, 3),
                'tipe_order' => $order->tipe_order ?: 'Dine-In',
                'nomor_meja' => $order->nomor_meja ?: 'Table 1',
                'catatan' => $order->catatan,
                'status_pembayaran' => $order->status_pembayaran ?: 'unpaid',
                'kitchen_status' => $order->kitchen_status ?: 'pending',
                'created_time' => $createdAt->format('h:i A'),
                'created_date' => $createdAt->format('d M Y'),
                'elapsed_seconds' => $elapsedSeconds,
                'elapsed_minutes' => $elapsedMinutes,
                'urgency' => $urgency,
                'cashier' => optional($order->user)->name ?? 'Cashier',
                'customer' => optional($order->member)->nama ?? 'Walk-in',
                'total_items' => (int)$order->total_item,
                'items' => $items,
                'kot_url' => route('kitchen.kot', $order->id_penjualan),
            ];
        });

        // Quick summary counts (respecting the same cashier scoping)
        $countQuery = function ($status) use ($isCashier, $request) {
            $q = Penjualan::where('kitchen_status', $status)
                ->where('total_item', '>', 0)
                ->whereDate('created_at', Carbon::today());

            if ($isCashier) {
                $q->where('id_user', auth()->id());
            } elseif ($request->filled('cashier_id') && $request->cashier_id !== 'all') {
                $q->where('id_user', $request->cashier_id);
            }
            return $q->count();
        };

        $counts = [
            'pending' => $countQuery('pending'),
            'cooking' => $countQuery('cooking'),
            'ready' => $countQuery('ready'),
            'served_today' => $countQuery('served'),
            'total_active' => ($countQuery('pending') + $countQuery('cooking') + $countQuery('ready')),
        ];

        return response()->json([
            'status' => 'success',
            'orders' => $formattedOrders,
            'counts' => $counts,
            'server_time' => $now->format('h:i:s A'),
            'is_cashier_scoped' => $isCashier,
            'user_name' => auth()->user()->name ?? 'Staff',
        ]);
    }

    /**
     * Update kitchen preparation status of an order.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,cooking,ready,served,cancelled',
        ]);

        $penjualan = Penjualan::findOrFail($id);
        $newStatus = $request->status;

        $penjualan->kitchen_status = $newStatus;

        if ($newStatus === 'cooking' && empty($penjualan->kitchen_started_at)) {
            $penjualan->kitchen_started_at = Carbon::now();
        } elseif ($newStatus === 'ready') {
            $penjualan->kitchen_ready_at = Carbon::now();
        } elseif ($newStatus === 'served') {
            $penjualan->kitchen_served_at = Carbon::now();
        } elseif ($newStatus === 'pending') {
            // Recall / Reset
            $penjualan->kitchen_started_at = null;
            $penjualan->kitchen_ready_at = null;
            $penjualan->kitchen_served_at = null;
        }

        $penjualan->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Order #' . tambah_nol_didepan($penjualan->id_penjualan, 5) . ' status updated to ' . ucfirst($newStatus),
            'kitchen_status' => $newStatus,
            'id_penjualan' => $penjualan->id_penjualan,
        ]);
    }

    /**
     * Print Kitchen Order Token (KOT) Thermal Slip (80mm / 58mm format).
     */
    public function kot($id)
    {
        $setting = Setting::first();
        $penjualan = Penjualan::with(['detail.produk.kategori', 'user', 'member'])->findOrFail($id);
        $detail = $penjualan->detail;

        return view('kitchen.kot', compact('setting', 'penjualan', 'detail'));
    }
}
