<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    /**
     * Display a listing of dining tables.
     */
    public function index()
    {
        Meja::syncStatuses();

        $totalTables = Meja::count();
        $availableTables = Meja::where('status', 'available')->count();
        $occupiedTables = Meja::where('status', 'occupied')->count();
        $reservedTables = Meja::where('status', 'reserved')->count();

        return view('meja.index', compact('totalTables', 'availableTables', 'occupiedTables', 'reservedTables'));
    }

    /**
     * Return datatables JSON for tables listing.
     */
    public function data()
    {
        Meja::syncStatuses();

        $tables = Meja::with('penjualanAktif.member')->orderBy('id_meja', 'asc')->get();

        return datatables()
            ->of($tables)
            ->addIndexColumn()
            ->addColumn('nomor_meja', function ($table) {
                return '<strong style="font-size:14px; color:#0f172a;"><i class="fa fa-cutlery" style="color:#0284c7; margin-right:6px;"></i>' . e($table->nomor_meja) . '</strong>';
            })
            ->addColumn('kapasitas', function ($table) {
                return '<span class="badge" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; font-weight:700; font-size:12px; padding:4px 8px;"><i class="fa fa-users"></i> ' . $table->kapasitas . ' Seats</span>';
            })
            ->addColumn('status', function ($table) {
                if ($table->status === 'occupied') {
                    return '<span class="badge" style="background:#fee2e2; color:#b91c1c; border:1px solid #fca5a5; padding:5px 10px; border-radius:6px; font-weight:700; font-size:12px;"><i class="fa fa-circle" style="color:#ef4444; font-size:9px;"></i> Occupied</span>';
                } elseif ($table->status === 'reserved') {
                    return '<span class="badge" style="background:#fef3c7; color:#b45309; border:1px solid #fde68a; padding:5px 10px; border-radius:6px; font-weight:700; font-size:12px;"><i class="fa fa-bookmark" style="font-size:10px;"></i> Reserved</span>';
                }
                return '<span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac; padding:5px 10px; border-radius:6px; font-weight:700; font-size:12px;"><i class="fa fa-check-circle" style="font-size:10px;"></i> Available</span>';
            })
            ->addColumn('active_order', function ($table) {
                if ($table->penjualanAktif) {
                    $inv = '#INV-' . tambah_nol_didepan($table->penjualanAktif->id_penjualan, 5);
                    $total = format_currency($table->penjualanAktif->bayar);
                    $cust = $table->penjualanAktif->member->nama ?? $table->penjualanAktif->nama_pelanggan ?? 'Walk-in';
                    return '<div style="font-size:12px;"><strong style="color:#ea580c;">' . $inv . '</strong> (' . $total . ')<br><span class="text-muted">' . e($cust) . '</span></div>';
                }
                return '<span class="text-muted" style="font-size:12px;">-</span>';
            })
            ->addColumn('aksi', function ($table) {
                return '
                <div class="table-actions-group">
                    <button type="button" onclick="editForm(`' . route('meja.update', $table->id_meja) . '`)" class="btn-table-action btn-edit" title="Edit Table"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`' . route('meja.destroy', $table->id_meja) . '`)" class="btn-table-action btn-delete" title="Delete Table"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['nomor_meja', 'kapasitas', 'status', 'active_order', 'aksi'])
            ->make(true);
    }

    /**
     * Store a newly created dining table.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|string|max:50|unique:meja,nomor_meja',
            'kapasitas' => 'required|integer|min:1|max:50',
            'status' => 'required|in:available,occupied,reserved',
        ]);

        Meja::create([
            'nomor_meja' => trim($request->nomor_meja),
            'kapasitas' => (int)$request->kapasitas,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Table added successfully!'
        ], 200);
    }

    /**
     * Display specified table.
     */
    public function show($id)
    {
        $table = Meja::findOrFail($id);
        return response()->json($table);
    }

    /**
     * Update specified table.
     */
    public function update(Request $request, $id)
    {
        $table = Meja::findOrFail($id);

        $request->validate([
            'nomor_meja' => 'required|string|max:50|unique:meja,nomor_meja,' . $table->id_meja . ',id_meja',
            'kapasitas' => 'required|integer|min:1|max:50',
            'status' => 'required|in:available,occupied,reserved',
        ]);

        $table->nomor_meja = trim($request->nomor_meja);
        $table->kapasitas = (int)$request->kapasitas;
        $table->status = $request->status;
        $table->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Table updated successfully!'
        ], 200);
    }

    /**
     * Remove the specified table.
     */
    public function destroy($id)
    {
        $table = Meja::findOrFail($id);

        if ($table->status === 'occupied' && !empty($table->id_penjualan_aktif)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete table while it is occupied by an active order!'
            ], 422);
        }

        $table->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Table deleted successfully!'
        ], 200);
    }

    /**
     * Manually release / free an occupied or reserved table.
     */
    public function freeTable($id)
    {
        $table = Meja::findOrFail($id);
        $table->status = 'available';
        $table->id_penjualan_aktif = null;
        $table->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Table ' . $table->nomor_meja . ' is now released and Available!'
        ]);
    }
}
