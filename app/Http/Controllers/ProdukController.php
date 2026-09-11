<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use PDF;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');

        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')
            ->select('produk.*', 'kategori.nama_kategori')
            ->orderBy('produk.id_produk', 'desc')
            ->get();

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_produk[]" value="'. $produk->id_produk .'">
                ';
            })
            ->addColumn('foto_preview', function ($produk) {
                $imgUrl = !empty($produk->foto) ? url($produk->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100&auto=format&fit=crop&q=80';
                return '<img src="'. $imgUrl .'" style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">';
            })
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-success">'. $produk->kode_produk .'</span>';
            })
            ->addColumn('harga_beli', function ($produk) {
                return format_currency($produk->harga_beli);
            })
            ->addColumn('harga_jual', function ($produk) {
                return format_currency($produk->harga_jual);
            })
            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->addColumn('aksi', function ($produk) {
                return '
                <div class="table-actions-group">
                    <button type="button" onclick="editForm(`'. route('produk.update', $produk->id_produk) .'`)" class="btn-table-action btn-edit" title="Edit Menu Item"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('produk.destroy', $produk->id_produk) .'`)" class="btn-table-action btn-delete" title="Delete Menu Item"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'foto_preview', 'kode_produk', 'select_all'])
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
        $latest = Produk::latest('id_produk')->first();
        $nextId = $latest ? (int)$latest->id_produk + 1 : 1;
        $requestData = $request->all();
        $requestData['kode_produk'] = 'P' . tambah_nol_didepan($nextId, 6);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $uploadDir = public_path('img/produk');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $nama = 'produk-' . date('YmdHis') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $nama);
            $requestData['foto'] = "/img/produk/$nama";
        } else {
            unset($requestData['foto']);
        }

        Produk::create($requestData);

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
        $produk = Produk::find($id);

        return response()->json($produk);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $requestData = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $uploadDir = public_path('img/produk');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Unlink old photo if exists
            if (!empty($produk->foto) && file_exists(public_path($produk->foto))) {
                @unlink(public_path($produk->foto));
            }

            $nama = 'produk-' . date('YmdHis') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $nama);
            $requestData['foto'] = "/img/produk/$nama";
        } else {
            unset($requestData['foto']);
        }

        $produk->update($requestData);

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
        $produk = Produk::find($id);
        if ($produk) {
            if (!empty($produk->foto) && file_exists(public_path($produk->foto))) {
                @unlink(public_path($produk->foto));
            }
            $produk->delete();
        }

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        if (!empty($request->id_produk) && is_array($request->id_produk)) {
            foreach ($request->id_produk as $id) {
                $produk = Produk::find($id);
                if ($produk) {
                    if (!empty($produk->foto) && file_exists(public_path($produk->foto))) {
                        @unlink(public_path($produk->foto));
                    }
                    $produk->delete();
                }
            }
        }

        return response(null, 204);
    }
    // visit "codeastro" for more projects!
    public function cetakBarcode(Request $request)
    {
        $dataproduk = array();
        if (!empty($request->id_produk) && is_array($request->id_produk)) {
            foreach ($request->id_produk as $id) {
                $produk = Produk::find($id);
                if ($produk) {
                    $dataproduk[] = $produk;
                }
            }
        }

        $no  = 1;
        $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('product.pdf');
    }
}

