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

        // Sync table occupancy with current active unpaid dine-in orders
        \App\Models\Meja::syncStatuses();

        $activeOrdersQuery = Penjualan::where(function($q) {
                $q->whereIn('kitchen_status', ['pending', 'cooking', 'ready'])
                  ->orWhereNull('kitchen_status');
            })
            ->where('total_item', '>', 0)
            ->whereDate('created_at', Carbon::today());

        $deliveryQuery = Penjualan::where('tipe_order', 'Delivery')
            ->where('total_item', '>', 0)
            ->whereDate('created_at', Carbon::today());

        $todayTotalQuery = Penjualan::where('total_item', '>', 0)
            ->whereDate('created_at', Carbon::today());

        if ($userId) {
            $activeOrdersQuery->where('id_user', $userId);
            $deliveryQuery->where('id_user', $userId);
            $todayTotalQuery->where('id_user', $userId);
        }

        $activeOrdersCount = $activeOrdersQuery->count();
        $dineInCount = \App\Models\Meja::where('status', 'occupied')->count();
        $deliveryCount = $deliveryQuery->count();
        $todayTotalCount = $todayTotalQuery->count();

        // Get list of active cashiers/waiters for admin/manager filter
        $cashiersList = [];
        if (!$isCashier) {
            $cashiersList = User::orderBy('name')->get();
        }

        return view('kitchen.index', compact(
            'setting',
            'activeOrdersCount',
            'dineInCount',
            'deliveryCount',
            'todayTotalCount',
            'isCashier',
            'cashiersList'
        ));
    }

    /**
     * Return live active kitchen orders JSON for live dashboard monitor.
     */
    public function data(Request $request)
    {
        $typeFilter = $request->get('type', 'all'); // all, Dine-In, Takeaway, Delivery
        $statusTab = $request->get('tab', 'active'); // active, all_today
        $isCashier = $this->isRestrictedCashier();

        $query = Penjualan::with(['detail.produk.kategori', 'member', 'user'])
            ->where('total_item', '>', 0)
            ->whereDate('created_at', Carbon::today());

        if ($isCashier) {
            $query->where('id_user', auth()->id());
        } elseif ($request->filled('cashier_id') && $request->cashier_id !== 'all') {
            $query->where('id_user', $request->cashier_id);
        }

        if ($typeFilter !== 'all') {
            $query->where('tipe_order', $typeFilter);
        }

        if ($statusTab === 'active') {
            // In progress orders for kitchen monitor
            $query->where(function($q) {
                $q->whereIn('kitchen_status', ['pending', 'cooking', 'ready'])
                  ->orWhereNull('kitchen_status');
            });
        }

        $orders = $query->orderBy('id_penjualan', 'desc')->get();

        $now = Carbon::now();
        $formattedOrders = $orders->map(function ($order) use ($now) {
            $createdAt = Carbon::parse($order->created_at);
            $elapsedSeconds = $createdAt->diffInSeconds($now);
            $elapsedMinutes = floor($elapsedSeconds / 60);

            $items = $order->detail->map(function ($d) {
                return [
                    'id_produk' => $d->id_produk,
                    'nama_produk' => optional($d->produk)->nama_produk ?? 'Menu Item',
                    'jumlah' => (int)$d->jumlah,
                    'catatan' => $d->catatan ?? '',
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
                'nama_pelanggan' => $order->nama_pelanggan,
                'telepon_pelanggan' => $order->telepon_pelanggan,
                'alamat_pengiriman' => $order->alamat_pengiriman,
                'ongkir' => $order->ongkir,
                'status_pembayaran' => $order->status_pembayaran ?: 'unpaid',
                'kitchen_status' => $order->kitchen_status ?: 'pending',
                'created_time' => $createdAt->format('h:i A'),
                'created_date' => $createdAt->format('d M Y'),
                'elapsed_seconds' => $elapsedSeconds,
                'elapsed_minutes' => $elapsedMinutes,
                'cashier' => optional($order->user)->name ?? 'Cashier',
                'customer' => optional($order->member)->nama ?? $order->nama_pelanggan ?? 'Walk-in',
                'total_items' => (int)$order->total_item,
                'items' => $items,
                'kot_url' => route('kitchen.kot', $order->id_penjualan),
                'receipt_url' => route('penjualan.nota_kecil', $order->id_penjualan),
            ];
        });

        // Live stats for dashboard KPI cards
        \App\Models\Meja::syncStatuses();
        $liveBusyTables = \App\Models\Meja::where('status', 'occupied')->count();
        $liveDeliveryCount = Penjualan::where('tipe_order', 'Delivery')->where('total_item', '>', 0)->whereDate('created_at', Carbon::today())->count();
        $liveTodayTotal = Penjualan::where('total_item', '>', 0)->whereDate('created_at', Carbon::today())->count();

        return response()->json([
            'status' => 'success',
            'orders' => $formattedOrders,
            'total_count' => $formattedOrders->count(),
            'busy_tables_count' => $liveBusyTables,
            'delivery_count' => $liveDeliveryCount,
            'today_total_count' => $liveTodayTotal,
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
