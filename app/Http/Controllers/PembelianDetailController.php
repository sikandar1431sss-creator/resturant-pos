<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        if (! $id_pembelian) {
            return redirect()->route('pembelian.index');
        }

        $pembelian = Pembelian::find($id_pembelian);
        if (! $pembelian) {
            return redirect()->route('pembelian.index');
        }

        $produk = Produk::orderBy('nama_produk')->get();
        $rawMaterials = RawMaterial::orderBy('nama_material')->get();
        $supplier = Supplier::find(session('id_supplier') ?: $pembelian->id_supplier);
        $diskon = $pembelian->diskon ?? 0;

        if (! $supplier) {
            return redirect()->route('pembelian.index');
        }

        return view('pembelian_detail.index', compact('id_pembelian', 'produk', 'rawMaterials', 'supplier', 'diskon'));
    }

    public function data($id)
    {
        $detail = PembelianDetail::with(['produk', 'rawMaterial'])
            ->where('id_pembelian', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $code = $item->rawMaterial->kode_material ?? $item->produk->kode_produk ?? '-';
            $name = $item->rawMaterial->nama_material ?? $item->produk->nama_produk ?? '-';
            $unit = $item->rawMaterial->satuan ?? '';
            $badge = $item->rawMaterial ? '<span class="label" style="background:#0284c7; color:#fff; font-size:10px; border-radius:3px; margin-left:4px;">Raw Stock</span>' : '';

            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. e($code) .'</span>';
            $row['nama_produk'] = '<strong>' . e($name) . '</strong> ' . $badge;
            $row['harga_beli']  = format_currency($item->harga_beli);
            $row['jumlah']      = '<div class="input-group" style="max-width:140px;">
                                    <input type="number" step="any" min="0.01" class="form-control input-sm quantity" data-id="'. $item->id_pembelian_detail .'" value="'. (float)$item->jumlah .'">
                                    '. ($unit ? '<span class="input-group-addon" style="font-size:11px; font-weight:700;">'. e($unit) .'</span>' : '') .'
                                   </div>';
            $row['subtotal']    = format_currency($item->subtotal);
            $row['aksi']        = '<div class="table-actions-group">
                                    <button onclick="deleteData(`'. route('pembelian_detail.destroy', $item->id_pembelian_detail) .'`)" class="btn-table-action btn-delete" title="Delete Item"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += (float)$item->subtotal;
            $total_item += (float)$item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'harga_beli'  => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'nama_produk', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $id_pembelian = $request->id_pembelian;
        if (! $id_pembelian) {
            return response()->json(['message' => 'Purchase transaction ID is missing'], 400);
        }

        // 1. If scanned or typed code directly
        if (empty($request->id_raw_material) && empty($request->id_produk) && !empty($request->kode_produk)) {
            $code = trim($request->kode_produk);
            $mat = RawMaterial::where('kode_material', $code)->first();
            if ($mat) {
                $request->merge(['id_raw_material' => $mat->id]);
            } else {
                $prod = Produk::where('kode_produk', $code)->first();
                if ($prod) {
                    $request->merge(['id_produk' => $prod->id_produk]);
                }
            }
        }

        if (!empty($request->id_raw_material)) {
            $material = RawMaterial::find($request->id_raw_material);
            if (! $material) {
                return response()->json(['message' => 'Raw Material not found'], 400);
            }

            // Check if already added in this purchase
            $existing = PembelianDetail::where('id_pembelian', $id_pembelian)
                ->where('id_raw_material', $material->id)
                ->first();

            if ($existing) {
                $existing->jumlah += 1;
                $existing->subtotal = $existing->harga_beli * $existing->jumlah;
                $existing->update();
            } else {
                $detail = new PembelianDetail();
                $detail->id_pembelian = $id_pembelian;
                $detail->id_raw_material = $material->id;
                $detail->id_produk = null;
                $detail->harga_beli = (float)($material->harga_beli ?: 0);
                $detail->jumlah = 1;
                $detail->subtotal = $detail->harga_beli;
                $detail->save();
            }

            return response()->json(['message' => 'Raw Material added to purchase'], 200);
        }

        if (!empty($request->id_produk)) {
            $produk = Produk::where('id_produk', $request->id_produk)->first();
            if (! $produk) {
                return response()->json(['message' => 'Product not found'], 400);
            }

            $existing = PembelianDetail::where('id_pembelian', $id_pembelian)
                ->where('id_produk', $produk->id_produk)
                ->first();

            if ($existing) {
                $existing->jumlah += 1;
                $existing->subtotal = $existing->harga_beli * $existing->jumlah;
                $existing->update();
            } else {
                $detail = new PembelianDetail();
                $detail->id_pembelian = $id_pembelian;
                $detail->id_produk = $produk->id_produk;
                $detail->id_raw_material = null;
                $detail->harga_beli = (float)($produk->harga_beli ?: 0);
                $detail->jumlah = 1;
                $detail->subtotal = $detail->harga_beli;
                $detail->save();
            }

            return response()->json(['message' => 'Product added to purchase'], 200);
        }

        return response()->json(['message' => 'No item selected or code not found'], 400);
    }

    public function update(Request $request, $id)
    {
        $detail = PembelianDetail::find($id);
        if ($detail) {
            $jumlah = max(0.001, (float) $request->jumlah);
            $harga_beli = (float) $detail->harga_beli;
            $detail->jumlah = $jumlah;
            $detail->subtotal = $harga_beli * $jumlah;
            $detail->update();
        }
        return response()->json(['message' => 'Updated'], 200);
    }

    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        if ($detail) {
            $detail->delete();
        }

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total = 0)
    {
        $total = (float) str_replace(',', '', (string)($total ?: 0));
        $diskon = (float) ($diskon ?: 0);
        $bayar = max(0, $total - ($diskon / 100 * $total));

        $data  = [
            'total' => $total,
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupees')
        ];

        return response()->json($data);
    }
}
