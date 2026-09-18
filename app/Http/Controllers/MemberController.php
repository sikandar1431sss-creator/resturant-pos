<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('member.index');
    }

    public function data()
    {
        $member = Member::orderBy('id_member', 'desc')->get();
        $allMemberSales = \App\Models\Penjualan::whereNotNull('id_member')->get();

        return datatables()
            ->of($member)
            ->addIndexColumn()
            ->addColumn('select_all', function ($item) {
                return '
                    <input type="checkbox" name="id_member[]" value="'. $item->id_member .'">
                ';
            })
            ->addColumn('kode_member', function ($item) {
                return '<span class="label label-success" style="font-weight:700; font-size:11px;">'. e($item->kode_member) .'</span>';
            })
            ->addColumn('orders_count', function ($item) use ($allMemberSales) {
                $count = $allMemberSales->where('id_member', $item->id_member)->count();
                return '<span class="badge" style="background:#0f172a; color:#fff; font-weight:700; font-size:11px; padding:3px 8px;">' . number_format($count) . '</span>';
            })
            ->addColumn('due_balance', function ($item) use ($allMemberSales) {
                $sales = $allMemberSales->where('id_member', $item->id_member);
                $invoiced = $sales->sum('bayar');
                $received = $sales->sum(function ($s) {
                    return min($s->bayar, (float)$s->diterima);
                });
                $due = max(0, $invoiced - $received);

                if ($due > 0.01) {
                    return '<span class="label label-danger" style="font-weight:800; font-size:11.5px; padding:3px 8px; border-radius:4px;">Due: ' . format_currency($due) . '</span>';
                }
                return '<span class="label label-success" style="font-weight:700; font-size:11px; padding:3px 8px; border-radius:4px;">Clear</span>';
            })
            ->addColumn('aksi', function ($item) {
                return '
                <div class="table-actions-group">
                    <a href="'. route('ledger.customer.statement', $item->id_member) .'" class="btn btn-xs btn-primary btn-flat" style="border-radius:6px; font-weight:700; padding:4px 10px; background:#8b5cf6; border-color:#8b5cf6; display:inline-flex; align-items:center; gap:5px; text-decoration:none;" title="View Customer Account Ledger">
                        <i class="fa fa-book"></i> Ledger
                    </a>
                    <button type="button" onclick="editForm(`'. route('member.update', $item->id_member) .'`)" class="btn-table-action btn-edit" title="Edit Customer"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('member.destroy', $item->id_member) .'`)" class="btn-table-action btn-delete" title="Delete Customer"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_member', 'orders_count', 'due_balance'])
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
        $member = Member::latest()->first() ?? new Member();
        $kode_member = (int) $member->kode_member +1;

        $member = new Member();
        $member->kode_member = tambah_nol_didepan($kode_member, 5);
        $member->nama = $request->nama;
        $member->telepon = $request->telepon;
        $member->alamat = $request->alamat;
        $member->save();

        return response()->json('Data saved successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = Member::find($id);

        return response()->json($member);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $member = Member::find($id)->update($request->all());

        return response()->json('Data saved successfully', 200);
    }
    // visit "codeastro" for more projects!
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::find($id);
        $member->delete();

        return response(null, 204);
    }

    public function cetakMember(Request $request)
    {
        $datamember = collect(array());
        foreach ($request->id_member as $id) {
            $member = Member::find($id);
            $datamember[] = $member;
        }

        $datamember = $datamember->chunk(2);
        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('member.cetak', compact('datamember', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('member.pdf');
    }
}
