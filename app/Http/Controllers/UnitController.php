<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function data()
    {
        $units = Unit::orderBy('id', 'asc')->get();

        return datatables()
            ->of($units)
            ->addIndexColumn()
            ->addColumn('nama_satuan', function ($unit) {
                return '<strong style="font-size:13.5px; color:#0f172a;">' . e($unit->nama_satuan) . '</strong>';
            })
            ->addColumn('simbol', function ($unit) {
                return '<span class="label" style="background:#1e3a68; color:#fff; font-size:11.5px; padding:3px 8px; border-radius:4px; font-weight:700;">' . e($unit->simbol ?: $unit->nama_satuan) . '</span>';
            })
            ->addColumn('deskripsi', function ($unit) {
                return '<span style="color:#475569; font-size:13px;">' . e($unit->deskripsi ?: '-') . '</span>';
            })
            ->addColumn('aksi', function ($unit) {
                return '
                <div class="table-actions-group">
                    <button type="button" onclick="editUnitForm('. $unit->id .')" class="btn-table-action btn-edit" title="Edit Unit"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteUnit(`'. route('unit.destroy', $unit->id) .'`)" class="btn-table-action btn-delete" title="Delete Unit"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['nama_satuan', 'simbol', 'deskripsi', 'aksi'])
            ->make(true);
    }

    public function list()
    {
        $units = Unit::orderBy('nama_satuan', 'asc')->get();
        return response()->json($units);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:units,nama_satuan',
            'simbol' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $unit = new Unit();
        $unit->nama_satuan = strtolower(trim($request->nama_satuan));
        $unit->simbol = $request->simbol ? trim($request->simbol) : strtolower(trim($request->nama_satuan));
        $unit->deskripsi = $request->deskripsi;
        $unit->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit ' . $unit->nama_satuan . ' added successfully!',
            'unit' => $unit
        ], 200);
    }

    public function show($id)
    {
        $unit = Unit::findOrFail($id);
        return response()->json($unit);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:units,nama_satuan,' . $unit->id,
            'simbol' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $unit->nama_satuan = strtolower(trim($request->nama_satuan));
        $unit->simbol = $request->simbol ? trim($request->simbol) : strtolower(trim($request->nama_satuan));
        $unit->deskripsi = $request->deskripsi;
        $unit->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit updated successfully!',
            'unit' => $unit
        ], 200);
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit deleted successfully!'
        ], 200);
    }
}
