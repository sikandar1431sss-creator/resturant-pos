<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        return view('supplier.index');
    }

    public function data()
    {
        $supplier = Supplier::orderBy('id_supplier', 'desc')->get();
        $allPurchases = \App\Models\Pembelian::all();

        return datatables()
            ->of($supplier)
            ->addIndexColumn()
            ->addColumn('po_count', function ($item) use ($allPurchases) {
                $count = $allPurchases->where('id_supplier', $item->id_supplier)->count();
                return '<span class="badge" style="background:#0f172a; color:#fff; font-weight:700; font-size:11px; padding:3px 8px;">' . number_format($count) . '</span>';
            })
            ->addColumn('due_balance', function ($item) use ($allPurchases) {
                $purchases = $allPurchases->where('id_supplier', $item->id_supplier);
                $billed = $purchases->sum(function ($p) {
                    $discountAmt = ($p->diskon ?? 0) / 100 * $p->total_harga;
                    return $p->total_harga - $discountAmt;
                });
                $paid = $purchases->sum('bayar');
                $due = max(0, $billed - $paid);

                if ($due > 0.01) {
                    return '<span class="label label-danger" style="font-weight:800; font-size:11.5px; padding:3px 8px; border-radius:4px;">Due: ' . format_currency($due) . '</span>';
                }
                return '<span class="label label-success" style="font-weight:700; font-size:11px; padding:3px 8px; border-radius:4px;">Clear</span>';
            })
            ->addColumn('aksi', function ($supplier) {
                return '
                <div class="table-actions-group">
                    <a href="'. route('ledger.supplier.statement', $supplier->id_supplier) .'" class="btn btn-xs btn-primary btn-flat" style="border-radius:6px; font-weight:700; padding:4px 10px; background:#0284c7; border-color:#0284c7; display:inline-flex; align-items:center; gap:5px; text-decoration:none;" title="View Supplier Account Ledger">
                        <i class="fa fa-book"></i> Ledger
                    </a>
                    <button type="button" onclick="editForm(`'. route('supplier.update', $supplier->id_supplier) .'`)" class="btn-table-action btn-edit" title="Edit Supplier"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('supplier.destroy', $supplier->id_supplier) .'`)" class="btn-table-action btn-delete" title="Delete Supplier"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'po_count', 'due_balance'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supplier = Supplier::create($request->all());

        return response()->json('Data saved successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::find($id);

        return response()->json($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    // visit "codeastro" for more projects!
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id)->update($request->all());

        return response()->json('Data saved successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id)->delete();

        return response(null, 204);
    }
}
// visit "codeastro" for more projects!