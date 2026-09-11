<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
        $penjualan = Penjualan::with(['member', 'user'])
            ->where('total_item', '>', 0)
            ->orderBy('id_penjualan', 'desc')
            ->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
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
                return '<strong style="color:#ea580c; font-size:13px;">#INV-'. tambah_nol_didepan($penjualan->id_penjualan, 5) .'</strong><br>' . $badge;
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
                $isPaid = (strtolower($penjualan->status_pembayaran ?? '') === 'paid' || $penjualan->diterima >= $penjualan->bayar);
                $payBtn = '';
                if (! $isPaid) {
                    $payBtn = '<button onclick="markAsPaid('. $penjualan->id_penjualan .', `'. '#INV-'. tambah_nol_didepan($penjualan->id_penjualan, 5) .'`, '. $penjualan->bayar .', `'. ($penjualan->member->nama ?? 'Walk-in') .'`)" class="btn-table-action btn-pay" title="Pay Now"><i class="fa fa-credit-card"></i> Pay</button>';
                }
                $printUrl = route('penjualan.nota_kecil', $penjualan->id_penjualan);
                return '
                <div class="table-actions-group">
                    ' . $payBtn . '
                    <a href="'. $printUrl .'" target="_blank" class="btn-table-action btn-print" title="Print Receipt in New Tab"><i class="fa fa-print"></i></a>
                    <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id_penjualan) .'`)" class="btn-table-action btn-view" title="View Order Items"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id_penjualan) .'`)" class="btn-table-action btn-delete" title="Delete Transaction"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'invoice', 'kode_member', 'total_item', 'bayar', 'metode_pembayaran', 'status_pembayaran', 'tanggal', 'diskon', 'kasir'])
            ->make(true);
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->nomor_meja = 'Table 1';
        $penjualan->tipe_order = 'Dine-In';
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->status_pembayaran = 'unpaid';
        $penjualan->metode_pembayaran = 'card';
        $penjualan->id_user = auth()->id() ?? 1;
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();

        $total_item = !empty($request->total_item) ? (int)$request->total_item : (int)$detail->sum('jumlah');
        $total_harga = !empty($request->total) ? (float)$request->total : (float)$detail->sum('subtotal');
        $diskon = !empty($request->diskon) ? (float)$request->diskon : 0;
        $bayar = !empty($request->bayar) ? (float)$request->bayar : ($total_harga - ($diskon / 100 * $total_harga));

        $status = $request->status_pembayaran ?? 'unpaid';
        $diterima = ($status === 'paid') ? $bayar : 0;

        $penjualan->id_member = !empty($request->id_member) ? $request->id_member : null;
        $penjualan->nomor_meja = $request->nomor_meja ?? $penjualan->nomor_meja ?? 'Table 1';
        $penjualan->tipe_order = $request->tipe_order ?? $penjualan->tipe_order ?? 'Dine-In';
        $penjualan->catatan = $request->catatan ?? $penjualan->catatan;
        $penjualan->total_item = $total_item;
        $penjualan->total_harga = $total_harga;
        $penjualan->diskon = $diskon;
        $penjualan->bayar = $bayar;
        $penjualan->diterima = $diterima;
        $penjualan->status_pembayaran = $status;
        $penjualan->metode_pembayaran = $request->metode_pembayaran ?? 'card';
        $penjualan->update();

        foreach ($detail as $item) {
            $item->diskon = $diskon;
            $item->update();

            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok = max(0, $produk->stok - $item->jumlah);
                $produk->update();
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice has been saved successfully.',
                'id_penjualan' => $penjualan->id_penjualan,
                'invoice' => '#INV-' . tambah_nol_didepan($penjualan->id_penjualan, 5),
                'status_pembayaran' => $status,
                'bayar' => $bayar,
                'print_url' => route('penjualan.nota_kecil', $penjualan->id_penjualan),
                'redirect_url' => route('transaksi.selesai')
            ], 200);
        }

        return redirect()->route('transaksi.selesai');
    }

    public function settlePayment(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $penjualan->diterima = $penjualan->bayar;
        $penjualan->status_pembayaran = 'paid';

        if (!empty($request->metode_pembayaran)) {
            $penjualan->metode_pembayaran = $request->metode_pembayaran;
        }

        $penjualan->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice #' . tambah_nol_didepan($penjualan->id_penjualan, 5) . ' marked as PAID successfully!',
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
        $bayar = !empty($request->bayar) ? (float)$request->bayar : ($total_harga - ($diskon / 100 * $total_harga));

        $penjualan->id_member = !empty($request->id_member) ? $request->id_member : null;
        $penjualan->nomor_meja = $request->nomor_meja ?? $penjualan->nomor_meja ?? 'Table 1';
        $penjualan->tipe_order = $request->tipe_order ?? $penjualan->tipe_order ?? 'Dine-In';
        $penjualan->catatan = $request->catatan ?? $penjualan->catatan;
        $penjualan->total_item = $total_item;
        $penjualan->total_harga = $total_harga;
        $penjualan->diskon = $diskon;
        $penjualan->bayar = $bayar;
        $penjualan->diterima = 0; // 0 indicates draft/on-hold
        $penjualan->sisa_bayar = $bayar;
        $penjualan->status_pembayaran = 'unpaid';
        $penjualan->update();

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
        $drafts = Penjualan::with(['member', 'user'])
            ->where('diterima', 0)
            ->where('total_item', '>', 0)
            ->orderBy('id_penjualan', 'desc')
            ->get();

        $data = [];
        foreach ($drafts as $d) {
            $details = PenjualanDetail::with('produk')->where('id_penjualan', $d->id_penjualan)->get();
            $itemsSummary = $details->map(function ($item) {
                return ($item->jumlah . 'x ' . ($item->produk->nama_produk ?? 'Dish'));
            })->implode(', ');

            $data[] = [
                'id_penjualan' => $d->id_penjualan,
                'invoice' => '#INV-' . tambah_nol_didepan($d->id_penjualan, 5),
                'nomor_meja' => $d->nomor_meja ?: 'Table 1',
                'tipe_order' => $d->tipe_order ?: 'Dine-In',
                'total_item' => $d->total_item,
                'total_harga' => format_currency($d->total_harga),
                'bayar' => format_currency($d->bayar),
                'bayar_raw' => $d->bayar,
                'member' => $d->member->nama ?? 'Walk-in',
                'items_summary' => $itemsSummary,
                'created_at' => date('d M, H:i', strtotime($d->created_at)),
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
        PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->delete();
        $penjualan->delete();

        if (session('id_penjualan') == $id) {
            session()->forget('id_penjualan');
        }

        return response()->json(['status' => 'success', 'message' => 'Draft invoice deleted successfully'], 200);
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
                return $detail->produk->nama_produk ?? 'Dish Item';
            })
            ->addColumn('harga_jual', function ($detail) {
                return format_currency($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return format_currency($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }
    // visit "codeastro" for more projects!
    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        if ($penjualan) {
            $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
            foreach ($detail as $item) {
                $produk = Produk::find($item->id_produk);
                if ($produk) {
                    $produk->stok += $item->jumlah;
                    $produk->update();
                }

                $item->delete();
            }

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