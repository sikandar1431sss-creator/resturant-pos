<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index()
    {
        $totalMaterials = RawMaterial::count();
        $inStockCount = RawMaterial::where('stok', '>', \DB::raw('min_stok'))->count();
        $lowStockCount = RawMaterial::where('stok', '<=', \DB::raw('min_stok'))->where('stok', '>', 0)->count();
        $outOfStockCount = RawMaterial::where('stok', '<=', 0)->count();
        $units = \App\Models\Unit::orderBy('nama_satuan')->get();

        return view('raw_material.index', compact('totalMaterials', 'inStockCount', 'lowStockCount', 'outOfStockCount', 'units'));
    }

    public function data()
    {
        $material = RawMaterial::orderBy('id', 'desc')->get();

        return datatables()
            ->of($material)
            ->addIndexColumn()
            ->addColumn('kode_material', function ($material) {
                return '<span class="label" style="background:#1e3a68; color:#fff; font-size:11.5px; padding:3px 8px; border-radius:4px; font-weight:700;">'. e($material->kode_material) .'</span>';
            })
            ->addColumn('nama_material', function ($material) {
                return '<strong style="font-size:13.5px; color:#0f172a;">'. e($material->nama_material) .'</strong>';
            })
            ->addColumn('satuan', function ($material) {
                return '<span class="badge" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; font-weight:700; font-size:11.5px; padding:3px 7px;">'. e(ucfirst($material->satuan)) .'</span>';
            })
            ->addColumn('stok_status', function ($material) {
                if ($material->stok <= 0) {
                    return '<span class="badge" style="background:#fee2e2; color:#b91c1c; border:1px solid #fca5a5; padding:4px 9px; border-radius:6px; font-weight:700; font-size:12px;"><i class="fa fa-times-circle"></i> 0 ' . e($material->satuan) . ' (Out of Stock)</span>';
                } elseif ($material->stok <= $material->min_stok) {
                    return '<span class="badge" style="background:#ffedd5; color:#c2410c; border:1px solid #fed7aa; padding:4px 9px; border-radius:6px; font-weight:700; font-size:12px;"><i class="fa fa-exclamation-triangle"></i> ' . $material->stok . ' ' . e($material->satuan) . ' (Low Stock)</span>';
                }
                return '<span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac; padding:4px 9px; border-radius:6px; font-weight:700; font-size:12px;"><i class="fa fa-check-circle"></i> ' . $material->stok . ' ' . e($material->satuan) . '</span>';
            })
            ->addColumn('min_stok', function ($material) {
                return '<span style="color:#64748b; font-weight:600; font-size:13px;">' . $material->min_stok . ' ' . e($material->satuan) . '</span>';
            })
            ->addColumn('harga_beli', function ($material) {
                return '<strong style="color:#0f172a; font-size:13.5px;">' . format_currency($material->harga_beli) . '</strong>';
            })
            ->addColumn('aksi', function ($material) {
                return '
                <div class="table-actions-group">
                    <button type="button" onclick="editForm(`'. route('raw_material.update', $material->id) .'`)" class="btn-table-action btn-edit" title="Edit Raw Material"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('raw_material.destroy', $material->id) .'`)" class="btn-table-action btn-delete" title="Delete Raw Material"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['kode_material', 'nama_material', 'satuan', 'stok_status', 'min_stok', 'harga_beli', 'aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_material' => 'required|string|max:255',
            'satuan' => 'required|string',
            'stok' => 'required|numeric|min:0',
            'min_stok' => 'required|numeric|min:0',
        ]);

        $latest = RawMaterial::latest('id')->first();
        $nextId = $latest ? ($latest->id + 1) : 1;
        $kode = 'RAW-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $material = RawMaterial::create([
            'kode_material' => $request->kode_material ?: $kode,
            'nama_material' => $request->nama_material,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'min_stok' => $request->min_stok,
            'harga_beli' => $request->harga_beli ?: 0,
        ]);

        return response()->json('Raw Material created successfully', 200);
    }

    public function show($id)
    {
        $material = RawMaterial::findOrFail($id);
        return response()->json($material);
    }

    public function update(Request $request, $id)
    {
        $material = RawMaterial::findOrFail($id);
        $material->nama_material = $request->nama_material;
        $material->satuan = $request->satuan;
        $material->stok = $request->stok;
        $material->min_stok = $request->min_stok;
        $material->harga_beli = $request->harga_beli ?: 0;
        $material->update();

        return response()->json('Raw Material updated successfully', 200);
    }

    public function destroy($id)
    {
        $material = RawMaterial::findOrFail($id);
        $material->delete();

        return response(null, 204);
    }

    public function adjust(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:add,subtract,set',
            'quantity' => 'required|numeric|min:0',
        ]);

        $material = RawMaterial::findOrFail($id);
        if ($request->type == 'add') {
            $material->stok += $request->quantity;
        } elseif ($request->type == 'subtract') {
            $material->stok = max(0, $material->stok - $request->quantity);
        } else {
            $material->stok = $request->quantity;
        }
        $material->update();

        return response()->json(['message' => 'Stock updated successfully', 'current_stock' => $material->stok]);
    }
}
