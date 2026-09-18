<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealItem;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DealController extends Controller
{
    public function index()
    {
        $products = Produk::whereDoesntHave('deal')
            ->orderBy('nama_produk')
            ->get();
            
        return view('deal.index', compact('products'));
    }

    public function data()
    {
        $deals = Deal::with(['items.produk'])->orderBy('id', 'desc')->get();

        return datatables()
            ->of($deals)
            ->addIndexColumn()
            ->addColumn('foto', function ($deal) {
                if (!empty($deal->foto)) {
                    $imgUrl = url($deal->foto);
                } else {
                    $imgUrl = 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=120&auto=format&fit=crop&q=80';
                }
                return '<img src="'. $imgUrl .'" style="width:48px; height:48px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0;">';
            })
            ->addColumn('kode_deal', function ($deal) {
                return '<span class="label" style="background:#1e3a68; color:#fff; font-size:11.5px; padding:3px 8px; border-radius:4px; font-weight:700;">'. $deal->kode_deal .'</span>';
            })
            ->addColumn('nama_deal', function ($deal) {
                $desc = !empty($deal->deskripsi) ? '<br><small class="text-muted" style="font-size:11.5px;">' . e($deal->deskripsi) . '</small>' : '';
                return '<strong style="color:#0f172a; font-size:13.5px;">'. e($deal->nama_deal) .'</strong>' . $desc;
            })
            ->addColumn('items_summary', function ($deal) {
                if ($deal->items->isEmpty()) {
                    return '<span class="text-muted" style="font-size:12px;">No dishes added</span>';
                }
                $badges = '';
                foreach ($deal->items as $item) {
                    $dishName = $item->produk->nama_produk ?? 'Dish';
                    $badges .= '<span class="badge" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; font-weight:600; font-size:11px; margin:2px; padding:3px 6px;">'
                        . $item->jumlah . 'x ' . e($dishName) . '</span> ';
                }
                return '<div style="max-width:280px; display:flex; flex-wrap:wrap; gap:3px;">' . $badges . '</div>';
            })
            ->addColumn('cost_price', function ($deal) {
                $cost = $deal->cost_price > 0 ? $deal->cost_price : $deal->calculateEstimatedCost();
                return '<span style="color:#64748b; font-weight:600;">' . format_currency($cost) . '</span>';
            })
            ->addColumn('harga_jual', function ($deal) {
                $cost = $deal->cost_price > 0 ? $deal->cost_price : $deal->calculateEstimatedCost();
                $profit = max(0, $deal->harga_jual - $cost);
                $profitBadge = $profit > 0 ? '<br><small class="text-success" style="font-weight:700;">+Profit: ' . format_currency($profit) . '</small>' : '';
                return '<strong style="color:#ea580c; font-size:14px;">'. format_currency($deal->harga_jual) .'</strong>' . $profitBadge;
            })
            ->addColumn('status', function ($deal) {
                if ($deal->status) {
                    return '<span class="label label-success" style="border-radius:4px; font-weight:700; font-size:11px; padding:3px 7px;">Active</span>';
                }
                return '<span class="label label-danger" style="border-radius:4px; font-weight:700; font-size:11px; padding:3px 7px;">Inactive</span>';
            })
            ->addColumn('aksi', function ($deal) {
                return '
                <div class="table-actions-group">
                    <button onclick="showDealDetails('. $deal->id .')" class="btn-table-action btn-view" title="View Deal Breakdown"><i class="fa fa-eye"></i></button>
                    <button onclick="editDealForm('. $deal->id .')" class="btn-table-action btn-edit" title="Edit Deal"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteDeal(`'. route('deal.destroy', $deal->id) .'`)" class="btn-table-action btn-delete" title="Delete Deal"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['foto', 'kode_deal', 'nama_deal', 'items_summary', 'cost_price', 'harga_jual', 'status', 'aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_deal' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        // Auto-generate code if empty
        $kode = $request->kode_deal;
        if (empty($kode)) {
            $latest = Deal::latest('id')->first();
            $nextNum = $latest ? ($latest->id + 1) : 1;
            $kode = 'DEAL-' . sprintf('%03d', $nextNum);
        }

        // Handle Photo Upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = 'deal_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('img/deals');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $fileName);
            $fotoPath = 'img/deals/' . $fileName;
        }

        $deal = new Deal();
        $deal->kode_deal = $kode;
        $deal->nama_deal = $request->nama_deal;
        $deal->deskripsi = $request->deskripsi;
        $deal->harga_jual = (float) $request->harga_jual;
        $deal->cost_price = !empty($request->cost_price) ? (float)$request->cost_price : 0;
        $deal->foto = $fotoPath;
        $deal->status = $request->has('status') ? 1 : ($request->status ?? 1);
        $deal->save();

        // Save Deal Items
        $calculatedCost = 0;
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                if (!empty($item['id_produk']) && !empty($item['jumlah']) && $item['jumlah'] > 0) {
                    $dealItem = new DealItem();
                    $dealItem->id_deal = $deal->id;
                    $dealItem->id_produk = $item['id_produk'];
                    $dealItem->jumlah = (int) $item['jumlah'];
                    $dealItem->save();

                    $p = Produk::find($item['id_produk']);
                    if ($p) {
                        $calculatedCost += ($p->harga_beli * $dealItem->jumlah);
                    }
                }
            }
        }

        $deal->cost_price = $calculatedCost > 0 ? $calculatedCost : (!empty($request->cost_price) ? (float)$request->cost_price : 0);
        $deal->update();

        // Sync corresponding POS Product
        $this->syncProductForDeal($deal);

        return response()->json([
            'status' => 'success',
            'message' => 'Deal ' . $deal->nama_deal . ' created successfully!'
        ], 200);
    }

    public function show($id)
    {
        $deal = Deal::with(['items.produk'])->findOrFail($id);
        $deal->estimated_cost = $deal->calculateEstimatedCost();
        $deal->currency_symbol = get_currency_symbol();
        return response()->json($deal);
    }

    public function update(Request $request, $id)
    {
        $deal = Deal::findOrFail($id);

        $request->validate([
            'nama_deal' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $deal->nama_deal = $request->nama_deal;
        if (!empty($request->kode_deal)) {
            $deal->kode_deal = $request->kode_deal;
        }
        $deal->deskripsi = $request->deskripsi;
        $deal->harga_jual = (float) $request->harga_jual;
        $deal->cost_price = !empty($request->cost_price) ? (float)$request->cost_price : 0;
        $deal->status = $request->has('status') ? ($request->status ? 1 : 0) : 1;

        // Handle Photo Upload
        if ($request->hasFile('foto')) {
            if (!empty($deal->foto) && file_exists(public_path($deal->foto))) {
                @unlink(public_path($deal->foto));
            }
            $file = $request->file('foto');
            $fileName = 'deal_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('img/deals');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $fileName);
            $deal->foto = 'img/deals/' . $fileName;
        }

        $deal->update();

        // Update items
        DealItem::where('id_deal', $deal->id)->delete();
        $calculatedCost = 0;
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                if (!empty($item['id_produk']) && !empty($item['jumlah']) && $item['jumlah'] > 0) {
                    $dealItem = new DealItem();
                    $dealItem->id_deal = $deal->id;
                    $dealItem->id_produk = $item['id_produk'];
                    $dealItem->jumlah = (int) $item['jumlah'];
                    $dealItem->save();

                    $p = Produk::find($item['id_produk']);
                    if ($p) {
                        $calculatedCost += ($p->harga_beli * $dealItem->jumlah);
                    }
                }
            }
        }

        $deal->cost_price = $calculatedCost > 0 ? $calculatedCost : (!empty($request->cost_price) ? (float)$request->cost_price : 0);
        $deal->update();

        // Sync corresponding POS Product
        $this->syncProductForDeal($deal);

        return response()->json([
            'status' => 'success',
            'message' => 'Deal ' . $deal->nama_deal . ' updated successfully!'
        ], 200);
    }

    public function destroy($id)
    {
        $deal = Deal::findOrFail($id);
        if ($deal->id_produk) {
            $prod = Produk::find($deal->id_produk);
            if ($prod) {
                $prod->delete();
            }
        }
        if (!empty($deal->foto) && file_exists(public_path($deal->foto))) {
            @unlink(public_path($deal->foto));
        }
        DealItem::where('id_deal', $deal->id)->delete();
        $deal->delete();

        return response()->json(['status' => 'success', 'message' => 'Deal deleted successfully'], 200);
    }

    /**
     * Create or update POS catalog product for the deal so it can be ordered seamlessly in POS Terminal
     */
    protected function syncProductForDeal(Deal $deal)
    {
        // Find or create "Deals" category
        $dealCategory = Kategori::where('nama_kategori', 'Deals')
            ->orWhere('nama_kategori', 'Deals & Combos')
            ->first();

        if ($dealCategory) {
            if ($dealCategory->nama_kategori !== 'Deals') {
                $dealCategory->nama_kategori = 'Deals';
                $dealCategory->save();
            }
        } else {
            $dealCategory = Kategori::create(['nama_kategori' => 'Deals']);
        }

        $product = null;
        if ($deal->id_produk) {
            $product = Produk::find($deal->id_produk);
        }

        if (! $product) {
            // Check if product with matching code exists
            $product = Produk::where('kode_produk', $deal->kode_deal)->first();
        }

        if (! $product) {
            $product = new Produk();
        }

        $product->id_kategori = $dealCategory->id_kategori;
        $product->kode_produk = $deal->kode_deal;
        $product->nama_produk = $deal->nama_deal;
        $product->merk = 'Deal Combo';
        $product->harga_beli = (int) ($deal->cost_price > 0 ? $deal->cost_price : $deal->calculateEstimatedCost());
        $product->harga_jual = (int) $deal->harga_jual;
        $product->diskon = 0;
        $product->stok = $deal->status ? 9999 : 0;
        $product->foto = $deal->foto;
        $product->save();

        if ($deal->id_produk != $product->id_produk) {
            $deal->id_produk = $product->id_produk;
            $deal->update();
        }
    }
}
