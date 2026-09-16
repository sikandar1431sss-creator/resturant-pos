<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Meja;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $produk = Produk::with('kategori')->orderBy('nama_produk')->get();
        $kategori = Kategori::all();
        $brands = Produk::whereNotNull('merk')->where('merk', '!=', '')->distinct()->pluck('merk');
        $member = Member::orderBy('nama')->get();
        $diskon = Setting::first()->diskon ?? 0;
        // Synchronize table occupancy with live active orders
        Meja::syncStatuses();
        $mejaList = Meja::orderBy('id_meja')->get();

        // Check whether there are any transactions in progress
        if ($id_penjualan = session('id_penjualan')) {
            $penjualan = Penjualan::find($id_penjualan);
            if (! $penjualan) {
                return redirect()->route('transaksi.baru');
            }

            // If Dine-In and table is occupied by ANOTHER transaction (and not editing an existing completed bill)
            if ($penjualan->tipe_order === 'Dine-In' && !session('is_editing')) {
                $isOccupiedByOther = Penjualan::where('tipe_order', 'Dine-In')
                    ->where('nomor_meja', $penjualan->nomor_meja)
                    ->where('id_penjualan', '!=', $penjualan->id_penjualan)
                    ->where('total_item', '>', 0)
                    ->where(function($q) {
                        $q->where('status_pembayaran', '!=', 'paid')
                          ->orWhere('diterima', '<', \Illuminate\Support\Facades\DB::raw('bayar'));
                    })
                    ->exists();

                if ($isOccupiedByOther) {
                    $firstFree = Meja::where('status', 'available')->orderBy('id_meja')->first();
                    if ($firstFree) {
                        $penjualan->nomor_meja = $firstFree->nomor_meja;
                        $penjualan->update();
                    }
                }
            }

            $memberSelected = $penjualan->member ?? new Member();
            $isEditMode = session('is_editing', false) || ($penjualan->total_item > 0 && session()->has('is_editing'));
            $invoiceDiskon = $penjualan->diskon > 0 ? $penjualan->diskon : $diskon;

            return view('penjualan_detail.index', compact('produk', 'kategori', 'brands', 'member', 'diskon', 'invoiceDiskon', 'id_penjualan', 'penjualan', 'memberSelected', 'isEditMode', 'mejaList'));
        } else {
            return redirect()->route('transaksi.baru');
        }
    }

    public function data($id)
    {
        $detail = PenjualanDetail::with('produk.kategori')
            ->where('id_penjualan', $id)
            ->get();

        $items = [];
        $total = 0;
        $total_item = 0;
        $product_discount = 0;

        foreach ($detail as $item) {
            $subtotal = $item->subtotal;
            $original = $item->harga_jual * $item->jumlah;
            $item_discount = $original - $subtotal;
            $product_discount += $item_discount;

            $items[] = [
                'id_detail' => $item->id_penjualan_detail,
                'id_produk' => $item->id_produk,
                'nama_produk' => $item->produk['nama_produk'] ?? 'Food Item',
                'kode_produk' => $item->produk['kode_produk'] ?? 'ITEM',
                'harga_jual' => format_uang($item->harga_jual),
                'harga_raw' => $item->harga_jual,
                'jumlah' => $item->jumlah,
                'diskon' => $item->diskon,
                'catatan' => $item->catatan ?? '',
                'subtotal' => format_uang($item->subtotal),
                'subtotal_raw' => $item->subtotal,
                'delete_url' => route('transaksi.destroy', $item->id_penjualan_detail)
            ];

            $total += $item->subtotal;
            $total_item += $item->jumlah;
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
            'total_rp' => format_uang($total),
            'total_item' => $total_item,
            'product_discount' => $product_discount,
            'product_discount_rp' => format_uang($product_discount),
            'currency_symbol' => get_currency_symbol()
        ]);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data failed to save', 400);
        }

        // Auto-increment quantity if product already exists in this cart
        $existingDetail = PenjualanDetail::where('id_penjualan', $request->id_penjualan)
            ->where('id_produk', $produk->id_produk)
            ->first();

        if ($existingDetail) {
            $existingDetail->jumlah += 1;
            $existingDetail->subtotal = $existingDetail->harga_jual * $existingDetail->jumlah - (($existingDetail->diskon * $existingDetail->jumlah) / 100 * $existingDetail->harga_jual);
            $existingDetail->update();
        } else {
            $detail = new PenjualanDetail();
            $detail->id_penjualan = $request->id_penjualan;
            $detail->id_produk = $produk->id_produk;
            $detail->harga_jual = $produk->harga_jual;
            $detail->jumlah = 1;
            $detail->diskon = $produk->diskon;
            $detail->subtotal = $produk->harga_jual - ($produk->diskon / 100 * $produk->harga_jual);
            $detail->save();
        }

        return response()->json('Data saved successfully', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        if ($detail) {
            $detail->jumlah = max(1, (int) $request->jumlah);
            $detail->subtotal = $detail->harga_jual * $detail->jumlah - (($detail->diskon * $detail->jumlah) / 100 * $detail->harga_jual);
            $detail->update();
        }
        return response()->json('Updated', 200);
    }

    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        if ($detail) {
            $detail->delete();
        }

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar)),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali)),
        ];

        return response()->json($data);
    }

    public function updateNote(Request $request, $id)
    {
        $detail = PenjualanDetail::findOrFail($id);
        $detail->catatan = $request->catatan;
        $detail->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Cooking instruction saved',
            'catatan' => $detail->catatan
        ], 200);
    }
}