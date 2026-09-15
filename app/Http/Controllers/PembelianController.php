<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Setting;

class PembelianController extends Controller
{
    public function index()
    {
        $supplier = Supplier::orderBy('nama')->get();

        return view('pembelian.index', compact('supplier'));
    }

    public function data()
    {
        $pembelian = Pembelian::orderBy('id_pembelian', 'desc')->get();

        return datatables()
            ->of($pembelian)
            ->addIndexColumn()
            ->addColumn('total_item', function ($pembelian) {
                return format_uang($pembelian->total_item);
            })
            ->addColumn('total_harga', function ($pembelian) {
                return format_currency($pembelian->total_harga);
            })
            ->addColumn('bayar', function ($pembelian) {
                return format_currency($pembelian->bayar);
            })
            ->addColumn('status', function ($pembelian) {
                $net_total = $pembelian->total_harga - ($pembelian->diskon / 100 * $pembelian->total_harga);
                $due = max(0, $net_total - $pembelian->bayar);
                if ($due <= 0) {
                    return '<span class="label label-success" style="font-size:11px; padding:3px 8px; border-radius:4px; font-weight:700;">Paid</span>';
                } else {
                    return '<span class="label label-danger" style="font-size:11px; padding:3px 8px; border-radius:4px; font-weight:700;">Due: ' . format_currency($due) . '</span>';
                }
            })
            ->addColumn('tanggal', function ($pembelian) {
                return tanggal_indonesia($pembelian->created_at, false);
            })
            ->addColumn('supplier', function ($pembelian) {
                return $pembelian->supplier->nama ?? 'N/A';
            })
            ->editColumn('diskon', function ($pembelian) {
                return $pembelian->diskon . '%';
            })
            ->addColumn('aksi', function ($pembelian) {
                $net_total = $pembelian->total_harga - ($pembelian->diskon / 100 * $pembelian->total_harga);
                $due = max(0, $net_total - $pembelian->bayar);
                
                $payBtn = '';
                if ($due > 0) {
                    $payBtn = '<button onclick="openPayDueModal('. $pembelian->id_pembelian .', '. $due .', `'. ($pembelian->supplier->nama ?? 'Supplier') .'`, '. $net_total .', '. $pembelian->bayar .')" class="btn-table-action btn-pay" title="Pay Remaining Due"><i class="fa fa-money"></i></button>';
                }

                $printUrl = route('pembelian.nota_kecil', $pembelian->id_pembelian);
                $editUrl = route('pembelian.edit', $pembelian->id_pembelian);
                return '
                <div class="table-actions-group">
                    ' . $payBtn . '
                    <a href="'. $printUrl .'" target="_blank" class="btn-table-action btn-print" title="Print Thermal Slip"><i class="fa fa-print"></i></a>
                    <a href="'. $editUrl .'" class="btn-table-action btn-edit" title="Edit Purchase"><i class="fa fa-pencil"></i></a>
                    <button onclick="showDetail(`'. route('pembelian.show', $pembelian->id_pembelian) .'`, '. $pembelian->id_pembelian .')" class="btn-table-action btn-view" title="View Detail"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('pembelian.destroy', $pembelian->id_pembelian) .'`)" class="btn-table-action btn-delete" title="Delete Purchase"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    public function settlePayment(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $net_total = $pembelian->total_harga - ($pembelian->diskon / 100 * $pembelian->total_harga);
        $currentDue = max(0, $net_total - $pembelian->bayar);

        $amount = (float) ($request->amount_paid ?? $request->amount ?? $currentDue);

        if ($amount <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment amount must be greater than 0.'
            ], 422);
        }

        if ($amount > $currentDue) {
            $amount = $currentDue;
        }

        $pembelian->bayar += $amount;
        $pembelian->update();

        $newDue = max(0, $net_total - $pembelian->bayar);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment of ' . format_currency($amount) . ' successfully paid to supplier!' . ($newDue <= 0 ? ' (Fully Settled)' : ' (Remaining Due: ' . format_currency($newDue) . ')'),
            'new_bayar' => $pembelian->bayar,
            'bayar_rp' => format_currency($pembelian->bayar),
            'due' => $newDue,
            'due_rp' => format_currency($newDue)
        ]);
    }

    public function notaKecil($id)
    {
        $setting = Setting::first();
        $pembelian = Pembelian::with('supplier')->findOrFail($id);
        $detail = PembelianDetail::with('produk')->where('id_pembelian', $id)->get();

        return view('pembelian.nota_kecil', compact('setting', 'pembelian', 'detail'));
    }

    public function edit($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        session(['id_pembelian' => $pembelian->id_pembelian]);
        session(['id_supplier' => $pembelian->id_supplier]);

        return redirect()->route('pembelian_detail.index');
    }

    public function create($id)
    {
        $pembelian = new Pembelian();
        $pembelian->id_supplier = $id;
        $pembelian->total_item  = 0;
        $pembelian->total_harga = 0;
        $pembelian->diskon      = 0;
        $pembelian->bayar       = 0;
        $pembelian->save();

        session(['id_pembelian' => $pembelian->id_pembelian]);
        session(['id_supplier' => $pembelian->id_supplier]);

        return redirect()->route('pembelian_detail.index');
    }

    public function store(Request $request)
    {
        $pembelian = Pembelian::findOrFail($request->id_pembelian);
        $detail = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();

        $total_item = !empty($request->total_item) ? (int)$request->total_item : (int)$detail->sum('jumlah');
        $total_harga = !empty($request->total) ? (float)$request->total : (float)$detail->sum('subtotal');
        $diskon = !empty($request->diskon) ? (float)$request->diskon : 0;
        $bayar = !empty($request->bayar) ? (float)$request->bayar : ($total_harga - ($diskon / 100 * $total_harga));

        $pembelian->total_item = $total_item;
        $pembelian->total_harga = $total_harga;
        $pembelian->diskon = $diskon;
        $pembelian->bayar = $bayar;
        $pembelian->update();

        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk->stok += $item->jumlah;
            $produk->update();
        }

        return redirect()->route('pembelian.index');
    }

    public function show($id)
    {
        $detail = PembelianDetail::with('produk')->where('id_pembelian', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_beli', function ($detail) {
                return format_currency($detail->harga_beli);
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

    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);
        $detail    = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok -= $item->jumlah;
                $produk->update();
            }
            $item->delete();
        }

        $pembelian->delete();

        return response(null, 204);
    }
}
