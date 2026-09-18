<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        $meja = Meja::orderBy('nomor_meja')->get();
        $members = Member::orderBy('nama')->get();

        return view('penjualan.index', compact('users', 'meja', 'members'));
    }

    public function data(Request $request)
    {
        $query = Penjualan::with(['member', 'user'])
            ->where('total_item', '>', 0);

        // Strict Multi-Cashier Scoping: If user lacks 'sales.view_all' (and is not Admin/Manager), show only own transactions
        if (auth()->check() && !auth()->user()->hasRole('admin') && !auth()->user()->can('sales.view_all') && auth()->user()->level != 1) {
            $query->where('id_user', auth()->id());
        }

        // Date Range Filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Status Pembayaran Filter
        if ($request->filled('status_pembayaran') && $request->status_pembayaran !== 'all') {
            if ($request->status_pembayaran === 'paid') {
                $query->where(function($q) {
                    $q->where('status_pembayaran', 'paid')
                      ->orWhereRaw('diterima >= bayar');
                });
            } elseif ($request->status_pembayaran === 'unpaid') {
                $query->where(function($q) {
                    $q->where('status_pembayaran', '!=', 'paid')
                      ->whereRaw('(diterima < bayar OR status_pembayaran IS NULL OR status_pembayaran = "")');
                });
            }
        }

        // Payment Method Filter
        if ($request->filled('metode_pembayaran') && $request->metode_pembayaran !== 'all') {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        // Dining / Order Type Filter
        if ($request->filled('tipe_order') && $request->tipe_order !== 'all') {
            $query->where('tipe_order', $request->tipe_order);
        }

        // Cashier / User Filter
        if ($request->filled('id_user') && $request->id_user !== 'all') {
            $query->where('id_user', $request->id_user);
        }

        // Table Filter
        if ($request->filled('nomor_meja') && $request->nomor_meja !== 'all') {
            $query->where('nomor_meja', $request->nomor_meja);
        }

        // Calculate KPI stats on filtered query
        $statsQuery = clone $query;
        $allMatching = $statsQuery->get();
        $totalCount = $allMatching->count();
        $totalAmount = (float) $allMatching->sum('bayar');
        $paidCount = 0;
        $paidAmount = 0;
        $unpaidCount = 0;
        $unpaidAmount = 0;

        foreach ($allMatching as $inv) {
            $isPaid = (strtolower($inv->status_pembayaran ?? '') === 'paid' || $inv->diterima >= $inv->bayar);
            if ($isPaid) {
                $paidCount++;
                $paidAmount += (float) $inv->bayar;
            } else {
                $unpaidCount++;
                $unpaidAmount += (float) $inv->bayar;
            }
        }

        $penjualan = $query->orderBy('id_penjualan', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->with('stats', [
                'total_count' => $totalCount,
                'total_amount' => format_currency($totalAmount),
                'paid_count' => $paidCount,
                'paid_amount' => format_currency($paidAmount),
                'unpaid_count' => $unpaidCount,
                'unpaid_amount' => format_currency($unpaidAmount),
            ])
            ->addColumn('invoice', function ($penjualan) {
                $type = $penjualan->tipe_order ?? 'Dine-In';
                $badge = '';
                if ($type === 'Delivery') {
                    $badge = '<span class="label label-info" style="font-size:10px; border-radius:3px;">Delivery</span>';
                } elseif ($type === 'Takeaway') {
                    $badge = '<span class="label label-warning" style="font-size:10px; border-radius:3px;">Takeaway</span>';
                } else {
                    $table = !empty($penjualan->nomor_meja) ? $penjualan->nomor_meja : 'Dine-In';
                    $badge = '<span class="label label-primary" style="font-size:10px; border-radius:3px;">'. $table .'</span>';
                }

                $kStatus = strtolower($penjualan->kitchen_status ?? 'pending');
                $kBadge = '<span class="label" style="background:#f59e0b; color:#fff; font-size:9.5px; border-radius:3px; margin-left:3px;"><i class="fa fa-cutlery"></i> Pending</span>';
                if ($kStatus === 'cooking') {
                    $kBadge = '<span class="label" style="background:#ea580c; color:#fff; font-size:9.5px; border-radius:3px; margin-left:3px;"><i class="fa fa-fire"></i> Cooking</span>';
                } elseif ($kStatus === 'ready') {
                    $kBadge = '<span class="label label-success" style="font-size:9.5px; border-radius:3px; margin-left:3px;"><i class="fa fa-check"></i> Ready</span>';
                } elseif ($kStatus === 'served') {
                    $kBadge = '<span class="label label-default" style="font-size:9.5px; border-radius:3px; margin-left:3px;"><i class="fa fa-check-circle"></i> Served</span>';
                }

                return '<strong style="color:#ea580c; font-size:13px;">#INV-'. tambah_nol_didepan($penjualan->id_penjualan, 5) .'</strong><br>' . $badge . ' ' . $kBadge;
            })
            ->addColumn('total_item', function ($penjualan) {
                return '<span class="badge" style="background:#f1f5f9; color:#475569; font-weight:700;">' . format_uang($penjualan->total_item) . ' items</span>';
            })
            ->addColumn('bayar', function ($penjualan) {
                return '<strong style="color:#0f172a; font-size:14px;">'. format_currency($penjualan->bayar) .'</strong>';
            })
            ->addColumn('metode_pembayaran', function ($penjualan) {
                $m = strtolower($penjualan->metode_pembayaran ?? 'cash');
                if ($m === 'card') {
                    return '<span class="label" style="background:#1e3a68; color:#fff; font-size:11px; padding:3px 7px; border-radius:4px; font-weight:600;">Debit Card</span>';
                } elseif ($m === 'online' || $m === 'e-wallet') {
                    return '<span class="label label-info" style="font-size:11px; padding:3px 7px; border-radius:4px; font-weight:600;">E-Wallet</span>';
                } else {
                    return '<span class="label label-default" style="color:#334155; background:#f1f5f9; border:1px solid #cbd5e1; font-size:11px; padding:3px 7px; border-radius:4px; font-weight:600;">Cash</span>';
                }
            })
            ->addColumn('status_pembayaran', function ($penjualan) {
                $status = strtolower($penjualan->status_pembayaran ?? '');
                if ($status === 'paid' || $penjualan->diterima >= $penjualan->bayar) {
                    return '<span class="label label-success" style="font-size:11.5px; padding:4px 9px; border-radius:4px; font-weight:700;">Paid</span>';
                } else {
                    return '<span class="label label-danger" style="font-size:11.5px; padding:4px 9px; border-radius:4px; font-weight:700;">Unpaid</span>';
                }
            })
            ->addColumn('tanggal', function ($penjualan) {
                return '<span style="font-size:12px; color:#334155;">' . date('d M Y', strtotime($penjualan->created_at)) . '<br><small class="text-muted">' . date('h:i A', strtotime($penjualan->created_at)) . '</small></span>';
            })
            ->addColumn('kode_member', function ($penjualan) {
                if (!empty($penjualan->member->nama)) {
                    return '<strong style="color:#0f172a;">'. $penjualan->member->nama .'</strong><br><small class="text-muted">'. ($penjualan->member->telepon ?? '') .'</small>';
                }
                return '<span class="label label-default" style="color:#64748b; background:#f1f5f9; border:1px solid #e2e8f0;">Walk-in</span>';
            })
            ->editColumn('diskon', function ($penjualan) {
                return $penjualan->diskon > 0 ? '<span class="text-success font-weight-bold">-' . $penjualan->diskon . '%</span>' : '0%';
            })
            ->editColumn('kasir', function ($penjualan) {
                return '<span class="text-muted">' . ($penjualan->user->name ?? 'Admin') . '</span>';
            })
            ->addColumn('aksi', function ($penjualan) {
                $user = auth()->user();
                $isAdmin = ($user && ($user->hasRole('admin') || $user->level == 1));

                $isPaid = (strtolower($penjualan->status_pembayaran ?? '') === 'paid' || $penjualan->diterima >= $penjualan->bayar);
                
                // 1. Settle Payment (Pay Now)
                $payBtn = '';
                if (! $isPaid && ($isAdmin || $user->can('pos.settle_payment'))) {
                    $payBtn = '<a href="javascript:void(0)" onclick="markAsPaid('. $penjualan->id_penjualan .', `'. '#INV-'. tambah_nol_didepan($penjualan->id_penjualan, 5) .'`, '. $penjualan->bayar .', `'. ($penjualan->member->nama ?? 'Walk-in') .'`)" class="btn-table-action btn-pay" title="Pay Now"><i class="fa fa-money"></i></a>';
                }

                // 2. Kitchen KOT Slip
                $kotBtn = '';
                if ($isAdmin || $user->can('pos.print_kot') || $user->can('kitchen.access')) {
                    $kotUrl = route('kitchen.kot', $penjualan->id_penjualan);
                    $kotBtn = '<a href="'. $kotUrl .'" target="_blank" class="btn-table-action" style="background:#fff7ed; color:#ea580c; border:1px solid #fdba74;" title="Print Kitchen KOT"><i class="fa fa-cutlery"></i></a>';
                }

                // 3. Print Customer Receipt
                $printBtn = '';
                if ($isAdmin || $user->can('pos.print_bill')) {
                    $printUrl = route('penjualan.nota_kecil', $penjualan->id_penjualan);
                    $printBtn = '<a href="'. $printUrl .'" target="_blank" class="btn-table-action btn-print" title="Print Customer Receipt"><i class="fa fa-print"></i></a>';
                }

                // 4. Edit Saved Invoice
                $editBtn = '';
                if ($isAdmin || $user->can('sales.edit')) {
                    $editUrl = route('penjualan.edit', $penjualan->id_penjualan);
                    $editBtn = '<a href="'. $editUrl .'" class="btn-table-action btn-edit" title="Edit Invoice"><i class="fa fa-edit"></i></a>';
                }

                // 5. View Order Details
                $viewBtn = '';
                if ($isAdmin || $user->can('sales.view_all') || $user->can('sales.view_own') || $user->can('sales.view') || $user->can('pos.access')) {
                    $viewBtn = '<button onclick="showDetail(`'. route('penjualan.show', $penjualan->id_penjualan) .'`, '. $penjualan->id_penjualan .')" class="btn-table-action btn-view" title="View Order Items"><i class="fa fa-eye"></i></button>';
                }

                // 6. Delete Transaction
                $deleteBtn = '';
                if ($isAdmin || $user->can('sales.delete')) {
                    $deleteBtn = '<button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id_penjualan) .'`)" class="btn-table-action btn-delete" title="Delete Transaction"><i class="fa fa-trash"></i></button>';
                }

                return '
                <div class="table-actions-group">
                    ' . $payBtn . '
                    ' . $kotBtn . '
                    ' . $printBtn . '
                    ' . $editBtn . '
                    ' . $viewBtn . '
                    ' . $deleteBtn . '
                </div>
                ';
            })

            ->rawColumns(['aksi', 'invoice', 'kode_member', 'total_item', 'bayar', 'metode_pembayaran', 'status_pembayaran', 'tanggal', 'diskon', 'kasir', 'DT_RowIndex'])
            ->make(true);
    }

    public function revertStockForInvoice($penjualan)
    {
        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::with(['recipes.rawMaterial', 'deal.items.produk.recipes.rawMaterial'])->find($item->id_produk);
            if ($produk) {
                if ($produk->deal && $produk->deal->items->isNotEmpty()) {
                    // Deal combo: revert stock for each dish in the deal
                    foreach ($produk->deal->items as $dealDish) {
                        $subDish = $dealDish->produk;
                        if ($subDish) {
                            $totalDishQty = $dealDish->jumlah * $item->jumlah;
                            if ($subDish->recipes && $subDish->recipes->isNotEmpty()) {
                                foreach ($subDish->recipes as $recipe) {
                                    if ($recipe->rawMaterial) {
                                        $deductQty = $recipe->jumlah * $totalDishQty;
                                        $recipe->rawMaterial->stok += $deductQty;
                                        $recipe->rawMaterial->update();
                                    }
                                }
                            } else {
                                $subDish->stok += $totalDishQty;
                                $subDish->update();
                            }
                        }
                    }
                } elseif ($produk->recipes && $produk->recipes->isNotEmpty()) {
                    foreach ($produk->recipes as $recipe) {
                        if ($recipe->rawMaterial) {
                            $deductQty = $recipe->jumlah * $item->jumlah;
                            $recipe->rawMaterial->stok += $deductQty;
                            $recipe->rawMaterial->update();
                        }
                    }
                } else {
                    $produk->stok += $item->jumlah;
                    $produk->update();
                }
            }
        }
    }

    public function deductStockForInvoice($penjualan)
    {
        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::with(['recipes.rawMaterial', 'deal.items.produk.recipes.rawMaterial'])->find($item->id_produk);
            if ($produk) {
                if ($produk->deal && $produk->deal->items->isNotEmpty()) {
                    // Deal combo: deduct stock for each dish in the deal
                    foreach ($produk->deal->items as $dealDish) {
                        $subDish = $dealDish->produk;
                        if ($subDish) {
                            $totalDishQty = $dealDish->jumlah * $item->jumlah;
                            if ($subDish->recipes && $subDish->recipes->isNotEmpty()) {
                                foreach ($subDish->recipes as $recipe) {
                                    if ($recipe->rawMaterial) {
                                        $deductQty = $recipe->jumlah * $totalDishQty;
                                        $recipe->rawMaterial->stok = max(0, $recipe->rawMaterial->stok - $deductQty);
                                        $recipe->rawMaterial->update();
                                    }
                                }
                            } else {
                                $subDish->stok = max(0, $subDish->stok - $totalDishQty);
                                $subDish->update();
                            }
                        }
                    }
                } elseif ($produk->recipes && $produk->recipes->isNotEmpty()) {
                    foreach ($produk->recipes as $recipe) {
                        if ($recipe->rawMaterial) {
                            $deductQty = $recipe->jumlah * $item->jumlah;
                            $recipe->rawMaterial->stok = max(0, $recipe->rawMaterial->stok - $deductQty);
                            $recipe->rawMaterial->update();
                        }
                    }
                } else {
                    $produk->stok = max(0, $produk->stok - $item->jumlah);
                    $produk->update();
                }
            }
        }
    }

    public function create()
    {
        // If coming from an abandoned edit session, re-deduct stock
        if (session('is_editing') && session('id_penjualan')) {
            $existing = Penjualan::find(session('id_penjualan'));
            if ($existing) {
                $this->deductStockForInvoice($existing);
            }
            session()->forget(['is_editing', 'editing_invoice']);
        }

        // Reuse active empty transaction if it has 0 items
        $currentId = session('id_penjualan');
        if ($currentId) {
            $existingEmpty = Penjualan::find($currentId);
            if ($existingEmpty && $existingEmpty->total_item == 0 && PenjualanDetail::where('id_penjualan', $currentId)->count() == 0) {
                return redirect()->route('transaksi.index');
            }
        }

        // Clean up orphan empty transactions with 0 items
        Penjualan::where('total_item', 0)
            ->whereDoesntHave('detail')
            ->delete();

        // Synchronize table occupancy with real active orders
        Meja::syncStatuses();
        $freeTable = Meja::where('status', 'available')->orderBy('id_meja')->first();
        $defaultTable = $freeTable ? $freeTable->nomor_meja : 'Table 1';

        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->nomor_meja = $defaultTable;
        $penjualan->tipe_order = 'Dine-In';
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->status_pembayaran = 'unpaid';
        $penjualan->metode_pembayaran = 'cash';
        $penjualan->id_user = auth()->id() ?? 1;
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function edit($id)
    {
        if (auth()->check() && !auth()->user()->hasRole('admin') && !auth()->user()->can('sales.edit') && auth()->user()->level != 1) {
            return redirect()->route('penjualan.index')->with('error', 'You do not have permission to edit saved invoices.');
        }

        $penjualan = Penjualan::findOrFail($id);

        // Revert previous stock deduction so changes in POS adjust cleanly on save
        $this->revertStockForInvoice($penjualan);

        session([
            'id_penjualan' => $penjualan->id_penjualan,
            'is_editing' => true,
            'editing_invoice' => '#INV-' . tambah_nol_didepan($penjualan->id_penjualan, 5)
        ]);

        return redirect()->route('transaksi.index');
    }

    public function cancelEdit()
    {
        $id = session('id_penjualan');
        if ($id && session('is_editing')) {
            $penjualan = Penjualan::find($id);
            if ($penjualan) {
                $this->deductStockForInvoice($penjualan);
            }
        }
        session()->forget(['id_penjualan', 'is_editing', 'editing_invoice']);
        return redirect()->route('transaksi.baru');
    }

    public function getInfo($id)
    {
        $penjualan = Penjualan::with(['member', 'user'])->findOrFail($id);
        $members = \App\Models\Member::orderBy('nama')->get();

        return response()->json([
            'id_penjualan' => $penjualan->id_penjualan,
            'invoice' => '#INV-' . tambah_nol_didepan($penjualan->id_penjualan, 5),
            'id_member' => $penjualan->id_member,
            'nomor_meja' => $penjualan->nomor_meja ?: 'Table 1',
            'tipe_order' => $penjualan->tipe_order ?: 'Dine-In',
            'total_item' => $penjualan->total_item,
            'total_harga' => $penjualan->total_harga,
            'total_harga_rp' => format_currency($penjualan->total_harga),
            'diskon' => $penjualan->diskon,
            'bayar' => $penjualan->bayar,
            'bayar_rp' => format_currency($penjualan->bayar),
            'diterima' => $penjualan->diterima,
            'status_pembayaran' => $penjualan->status_pembayaran ?: 'unpaid',
            'metode_pembayaran' => $penjualan->metode_pembayaran ?: 'cash',
            'catatan' => $penjualan->catatan,
            'created_at' => date('d M Y, h:i A', strtotime($penjualan->created_at)),
            'members' => $members,
            'edit_pos_url' => route('penjualan.edit', $penjualan->id_penjualan),
            'print_url' => route('penjualan.nota_kecil', $penjualan->id_penjualan),
        ]);
    }

    public function updateInfo(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $penjualan->nomor_meja = $request->nomor_meja ?? $penjualan->nomor_meja;
        $penjualan->tipe_order = $request->tipe_order ?? $penjualan->tipe_order;
        $penjualan->id_member = !empty($request->id_member) ? $request->id_member : null;
        $penjualan->metode_pembayaran = $request->metode_pembayaran ?? $penjualan->metode_pembayaran;
        $penjualan->catatan = $request->catatan ?? $penjualan->catatan;

        if ($request->has('diskon')) {
            $diskon = (float) $request->diskon;
            $penjualan->diskon = $diskon;
            $penjualan->bayar = max(0, $penjualan->total_harga - ($diskon / 100 * $penjualan->total_harga));
        }

        if ($request->has('status_pembayaran')) {
            $penjualan->status_pembayaran = $request->status_pembayaran;
            if ($request->status_pembayaran === 'paid') {
                $penjualan->diterima = $penjualan->bayar;
            } else {
                $penjualan->diterima = 0;
            }
        }

        $penjualan->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice #INV-' . tambah_nol_didepan($penjualan->id_penjualan, 5) . ' details updated successfully!',
            'invoice' => '#INV-' . tambah_nol_didepan($penjualan->id_penjualan, 5),
            'bayar' => $penjualan->bayar,
            'bayar_rp' => format_currency($penjualan->bayar),
            'status_pembayaran' => $penjualan->status_pembayaran,
        ]);
    }

    public function getTablesStatus()
    {
        // 1. Sync table statuses with active unpaid dine-in orders
        $tables = Meja::syncStatuses();

        $unpaidDineInOrders = Penjualan::where('tipe_order', 'Dine-In')
            ->where(function($q) {
                $q->where('status_pembayaran', '!=', 'paid')
                  ->orWhere('diterima', '<', DB::raw('bayar'));
            })
            ->where('total_item', '>', 0)
            ->get();

        $occupiedMap = [];
        foreach ($unpaidDineInOrders as $ord) {
            $tableName = trim(strtolower($ord->nomor_meja ?? ''));
            if ($tableName && !isset($occupiedMap[$tableName])) {
                $occupiedMap[$tableName] = $ord;
            }
        }

        foreach ($tables as $t) {
            $key = trim(strtolower($t->nomor_meja));
            if (isset($occupiedMap[$key])) {
                $activeOrder = $occupiedMap[$key];
                $t->active_invoice = '#INV-' . tambah_nol_didepan($activeOrder->id_penjualan, 5);
                $t->active_amount = format_currency($activeOrder->bayar);
                $t->active_time = date('h:i A', strtotime($activeOrder->created_at));
            } else {
                $t->active_invoice = null;
                $t->active_amount = null;
                $t->active_time = null;
            }
        }

        return response()->json($tables);
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();

        $requestedTable = $request->nomor_meja ?? $penjualan->nomor_meja ?? 'Table 1';
        $orderType = $request->tipe_order ?? $penjualan->tipe_order ?? 'Dine-In';

        // Check if table is occupied by ANOTHER active unpaid order
        if ($orderType === 'Dine-In' && !empty($requestedTable)) {
            $alreadyOccupied = Penjualan::where('tipe_order', 'Dine-In')
                ->where('nomor_meja', $requestedTable)
                ->where('id_penjualan', '!=', $penjualan->id_penjualan)
                ->where('total_item', '>', 0)
                ->where(function($q) {
                    $q->where('status_pembayaran', '!=', 'paid')
                      ->orWhere('diterima', '<', DB::raw('bayar'));
                })
                ->first();

            if ($alreadyOccupied) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Table [' . $requestedTable . '] is already occupied by active order #INV-' . tambah_nol_didepan($alreadyOccupied->id_penjualan, 5) . '. Please select a free table.'
                ], 422);
            }
        }

        $total_item = !empty($request->total_item) ? (int)$request->total_item : (int)$detail->sum('jumlah');
        $total_harga = !empty($request->total) ? (float)$request->total : (float)$detail->sum('subtotal');
        $diskon = !empty($request->diskon) ? (float)$request->diskon : 0;
        $ongkir = !empty($request->ongkir) ? (float)$request->ongkir : 0;
        
        $discountAmount = ($diskon / 100 * $total_harga);
        $bayar = !empty($request->bayar) ? (float)$request->bayar : max(0, ($total_harga - $discountAmount) + $ongkir);

        $status = $request->status_pembayaran ?? 'unpaid';
        $diterima = ($status === 'paid') ? $bayar : (!empty($request->diterima) ? (float)$request->diterima : 0);

        $penjualan->id_member = !empty($request->id_member) ? $request->id_member : null;
        $penjualan->nama_pelanggan = $request->nama_pelanggan ?? $penjualan->nama_pelanggan;
        $penjualan->telepon_pelanggan = $request->telepon_pelanggan ?? $penjualan->telepon_pelanggan;
        $penjualan->alamat_pengiriman = $request->alamat_pengiriman ?? $penjualan->alamat_pengiriman;
        $penjualan->ongkir = $ongkir;
        $penjualan->nomor_meja = $requestedTable;
        $penjualan->tipe_order = $orderType;
        $penjualan->catatan = $request->catatan ?? $penjualan->catatan;
        $penjualan->total_item = $total_item;
        $penjualan->total_harga = $total_harga;
        $penjualan->diskon = $diskon;
        $penjualan->bayar = $bayar;
        $penjualan->diterima = $diterima;
        $penjualan->sisa_bayar = max(0, $bayar - $diterima);
        $penjualan->status_pembayaran = $status;
        $penjualan->metode_pembayaran = $request->metode_pembayaran ?? 'cash';
        $penjualan->update();

        // Update table status if Dine-In
        if ($penjualan->tipe_order === 'Dine-In' && !empty($penjualan->nomor_meja)) {
            $meja = Meja::where('nomor_meja', $penjualan->nomor_meja)->first();
            if ($meja) {
                if ($status === 'paid') {
                    $meja->status = 'available';
                    $meja->id_penjualan_aktif = null;
                } else {
                    $meja->status = 'occupied';
                    $meja->id_penjualan_aktif = $penjualan->id_penjualan;
                }
                $meja->update();
            }
        }

        foreach ($detail as $item) {
            $item->diskon = $diskon;
            $item->update();
        }

        $this->deductStockForInvoice($penjualan);

        $isEditing = session('is_editing') ?? false;
        session()->forget(['is_editing', 'editing_invoice']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'is_editing' => $isEditing,
                'message' => $isEditing ? 'Invoice #INV-' . tambah_nol_didepan($penjualan->id_penjualan, 5) . ' updated successfully.' : 'Order saved successfully.',
                'id_penjualan' => $penjualan->id_penjualan,
                'invoice' => '#INV-' . tambah_nol_didepan($penjualan->id_penjualan, 5),
                'status_pembayaran' => $status,
                'bayar' => $bayar,
                'print_url' => route('penjualan.nota_kecil', $penjualan->id_penjualan),
                'kot_url' => route('kitchen.kot', $penjualan->id_penjualan),
                'redirect_url' => route('transaksi.selesai')
            ], 200);
        }

        return redirect()->route('transaksi.selesai');
    }

    public function settlePayment(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $penjualan->diterima = $penjualan->bayar;
        $penjualan->sisa_bayar = 0;
        $penjualan->status_pembayaran = 'paid';

        if (!empty($request->metode_pembayaran)) {
            $penjualan->metode_pembayaran = $request->metode_pembayaran;
        }

        $penjualan->update();

        // Release the table when payment is settled!
        if (!empty($penjualan->nomor_meja)) {
            Meja::where('nomor_meja', $penjualan->nomor_meja)
                ->orWhere('id_penjualan_aktif', $penjualan->id_penjualan)
                ->update(['status' => 'available', 'id_penjualan_aktif' => null]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice #' . tambah_nol_didepan($penjualan->id_penjualan, 5) . ' marked as PAID & Table Released!',
            'status_pembayaran' => 'paid',
            'print_url' => route('penjualan.nota_kecil', $penjualan->id_penjualan)
        ], 200);
    }

    public function saveDraft(Request $request)
    {
        $id_penjualan = $request->id_penjualan ?? session('id_penjualan');
        if (! $id_penjualan) {
            return response()->json(['error' => 'No active transaction found'], 400);
        }

        $penjualan = Penjualan::findOrFail($id_penjualan);
        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();

        if ($detail->isEmpty()) {
            return response()->json(['error' => 'Cart is empty, nothing to draft.'], 422);
        }

        $total_item = (int)$detail->sum('jumlah');
        $total_harga = (float)$detail->sum('subtotal');
        $diskon = !empty($request->diskon) ? (float)$request->diskon : 0;
        $ongkir = !empty($request->ongkir) ? (float)$request->ongkir : 0;
        $discountAmount = ($diskon / 100 * $total_harga);
        $bayar = !empty($request->bayar) ? (float)$request->bayar : max(0, ($total_harga - $discountAmount) + $ongkir);

        $requestedTable = $request->nomor_meja ?? $penjualan->nomor_meja ?? 'Table 1';
        $orderType = $request->tipe_order ?? $penjualan->tipe_order ?? 'Dine-In';

        // Check if table is occupied by ANOTHER active unpaid order
        if ($orderType === 'Dine-In' && !empty($requestedTable)) {
            $alreadyOccupied = Penjualan::where('tipe_order', 'Dine-In')
                ->where('nomor_meja', $requestedTable)
                ->where('id_penjualan', '!=', $penjualan->id_penjualan)
                ->where('total_item', '>', 0)
                ->where(function($q) {
                    $q->where('status_pembayaran', '!=', 'paid')
                      ->orWhere('diterima', '<', DB::raw('bayar'));
                })
                ->first();

            if ($alreadyOccupied) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Table [' . $requestedTable . '] is already occupied by active order #INV-' . tambah_nol_didepan($alreadyOccupied->id_penjualan, 5) . '. Please select a free table.'
                ], 422);
            }
        }

        $penjualan->id_member = !empty($request->id_member) ? $request->id_member : null;
        $penjualan->nama_pelanggan = $request->nama_pelanggan ?? $penjualan->nama_pelanggan;
        $penjualan->telepon_pelanggan = $request->telepon_pelanggan ?? $penjualan->telepon_pelanggan;
        $penjualan->alamat_pengiriman = $request->alamat_pengiriman ?? $penjualan->alamat_pengiriman;
        $penjualan->ongkir = $ongkir;
        $penjualan->nomor_meja = $requestedTable;
        $penjualan->tipe_order = $orderType;
        $penjualan->catatan = $request->catatan ?? $penjualan->catatan;
        $penjualan->total_item = $total_item;
        $penjualan->total_harga = $total_harga;
        $penjualan->diskon = $diskon;
        $penjualan->bayar = $bayar;
        $penjualan->diterima = 0; // 0 indicates draft/on-hold
        $penjualan->sisa_bayar = $bayar;
        $penjualan->status_pembayaran = 'unpaid';
        $penjualan->update();

        // Mark table as occupied for drafted dine-in order
        if ($penjualan->tipe_order === 'Dine-In' && !empty($penjualan->nomor_meja)) {
            $meja = Meja::where('nomor_meja', $penjualan->nomor_meja)->first();
            if ($meja) {
                $meja->status = 'occupied';
                $meja->id_penjualan_aktif = $penjualan->id_penjualan;
                $meja->update();
            }
        }

        // Clear active session so cashier starts fresh order
        session()->forget('id_penjualan');

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice #INV-' . tambah_nol_didepan($penjualan->id_penjualan, 5) . ' parked as Draft for ' . ($penjualan->nomor_meja ?: 'Takeaway'),
            'id_penjualan' => $penjualan->id_penjualan
        ], 200);
    }

    public function draftList()
    {
        $query = Penjualan::with(['member', 'user', 'detail.produk'])
            ->where(function ($q) {
                $q->where('diterima', 0)
                  ->orWhere('status_pembayaran', 'unpaid')
                  ->orWhereNull('status_pembayaran');
            })
            ->where(function ($q) {
                $q->where('total_item', '>', 0)
                  ->orWhereHas('detail');
            });

        // Strict Multi-Cashier Scoping for draft parked invoices
        if (auth()->check() && !auth()->user()->hasRole('admin') && !auth()->user()->can('sales.view_all') && auth()->user()->level != 1) {
            $query->where('id_user', auth()->id());
        }

        $drafts = $query->orderBy('id_penjualan', 'desc')->get();
        $currentSessionId = session('id_penjualan');

        $data = [];
        foreach ($drafts as $d) {
            $details = $d->detail;
            if (!$details || $details->isEmpty()) {
                $details = PenjualanDetail::with('produk')->where('id_penjualan', $d->id_penjualan)->get();
            }

            $itemsCount = $d->total_item > 0 ? (int)$d->total_item : (int)$details->sum('jumlah');

            if ($itemsCount === 0 && $details->isEmpty()) {
                continue;
            }

            $itemsSummary = $details->map(function ($item) {
                $dishName = $item->produk->nama_produk ?? 'Food Item';
                return ($item->jumlah . 'x ' . $dishName);
            })->implode(', ');

            $createdAtCarbon = \Carbon\Carbon::parse($d->created_at);

            $data[] = [
                'id_penjualan' => $d->id_penjualan,
                'invoice' => '#INV-' . tambah_nol_didepan($d->id_penjualan, 5),
                'nomor_meja' => $d->nomor_meja ?: ($d->tipe_order === 'Takeaway' ? 'Takeaway' : ($d->tipe_order === 'Delivery' ? 'Delivery' : 'Table 1')),
                'tipe_order' => $d->tipe_order ?: 'Dine-In',
                'total_item' => $itemsCount,
                'total_harga' => format_currency($d->total_harga),
                'bayar' => format_currency($d->bayar),
                'bayar_raw' => (float)$d->bayar,
                'member' => $d->member->nama ?? $d->nama_pelanggan ?? 'Walk-in',
                'telepon_pelanggan' => $d->telepon_pelanggan ?? '',
                'items_summary' => $itemsSummary ?: 'No items listed',
                'created_at' => $createdAtCarbon->format('d M, H:i'),
                'full_date' => $createdAtCarbon->format('d M Y, h:i A'),
                'time_ago' => $createdAtCarbon->diffForHumans(),
                'cashier_name' => $d->user->name ?? 'Cashier',
                'is_current' => ($currentSessionId == $d->id_penjualan),
                'resume_url' => route('transaksi.resume_draft', $d->id_penjualan),
                'delete_url' => route('transaksi.delete_draft', $d->id_penjualan)
            ];
        }

        return response()->json($data);
    }

    public function resumeDraft($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function deleteDraft($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        if (!empty($penjualan->nomor_meja)) {
            Meja::where('id_penjualan_aktif', $penjualan->id_penjualan)
                ->update(['status' => 'available', 'id_penjualan_aktif' => null]);
        }
        PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->delete();
        $penjualan->delete();

        if (session('id_penjualan') == $id) {
            session()->forget('id_penjualan');
        }

        return response()->json(['status' => 'success', 'message' => 'Draft invoice deleted & Table Released'], 200);
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. ($detail->produk->kode_produk ?? 'P000') .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                $notes = !empty($detail->catatan) ? '<br><small class="text-warning" style="font-weight:700;"><i class="fa fa-pencil"></i> ' . e($detail->catatan) . '</small>' : '';
                return ($detail->produk->nama_produk ?? 'Dish') . $notes;
            })
            ->addColumn('harga_jual', function ($detail) {
                return format_currency($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return '<strong style="font-size:13px;">' . format_uang($detail->jumlah) . '</strong>';
            })
            ->addColumn('diskon', function ($detail) {
                return $detail->diskon > 0 ? '<span class="text-success font-weight-bold">-' . $detail->diskon . '%</span>' : '0%';
            })
            ->addColumn('subtotal', function ($detail) {
                return format_currency($detail->subtotal);
            })
            ->rawColumns(['kode_produk', 'nama_produk', 'jumlah', 'diskon'])
            ->make(true);
    }
    public function destroy($id)
    {
        if (auth()->check() && !auth()->user()->hasRole('admin') && !auth()->user()->can('sales.delete') && auth()->user()->level != 1) {
            return response()->json(['status' => 'error', 'message' => 'Permission denied: Cannot delete sales invoices.'], 403);
        }

        $penjualan = Penjualan::find($id);
        if ($penjualan) {
            if (!empty($penjualan->nomor_meja)) {
                Meja::where('id_penjualan_aktif', $penjualan->id_penjualan)
                    ->update(['status' => 'available', 'id_penjualan_aktif' => null]);
            }
            $this->revertStockForInvoice($penjualan);
            PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->delete();
            $penjualan->delete();
        }

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();
        $id_penjualan = session('id_penjualan');
        $penjualan = Penjualan::with('user', 'member')->find($id_penjualan);
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id_penjualan)->get();

        return view('penjualan.selesai', compact('setting', 'penjualan', 'detail'));
    }

    public function notaKecilSingle($id)
    {
        $setting = Setting::first();
        $penjualan = Penjualan::with('member', 'user')->findOrFail($id);
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesarSingle($id)
    {
        $setting = Setting::first();
        $penjualan = Penjualan::with('member', 'user')->findOrFail($id);
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaction-'. date('Y-m-d-his') .'.pdf');
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $id_penjualan = session('id_penjualan');
        $penjualan = Penjualan::with('member')->find($id_penjualan);
        if (! $penjualan) {
            $penjualan = Penjualan::with('member')->latest('id_penjualan')->first();
            if (! $penjualan) {
                abort(404);
            }
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $penjualan->id_penjualan)
            ->get();
        
        if ($penjualan->total_harga == 0 && $detail->isNotEmpty()) {
            $penjualan->total_item = (int) $detail->sum('jumlah');
            $penjualan->total_harga = (float) $detail->sum('subtotal');
            $diskon = (float) ($penjualan->diskon ?? 0);
            $penjualan->bayar = $penjualan->total_harga - ($diskon / 100 * $penjualan->total_harga);
            if (!isset($penjualan->diterima) || $penjualan->diterima == 0) {
                $penjualan->diterima = $penjualan->bayar;
            }
        }

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $id_penjualan = session('id_penjualan');
        $penjualan = Penjualan::with('member')->find($id_penjualan);
        if (! $penjualan) {
            $penjualan = Penjualan::with('member')->latest('id_penjualan')->first();
            if (! $penjualan) {
                abort(404);
            }
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $penjualan->id_penjualan)
            ->get();

        if ($penjualan->total_harga == 0 && $detail->isNotEmpty()) {
            $penjualan->total_item = (int) $detail->sum('jumlah');
            $penjualan->total_harga = (float) $detail->sum('subtotal');
            $diskon = (float) ($penjualan->diskon ?? 0);
            $penjualan->bayar = $penjualan->total_harga - ($diskon / 100 * $penjualan->total_harga);
            if (!isset($penjualan->diterima) || $penjualan->diterima == 0) {
                $penjualan->diterima = $penjualan->bayar;
            }
        }

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaction-'. date('Y-m-d-his') .'.pdf');
    }
}