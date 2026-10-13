<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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

    /**
     * 1-Click Automatic CSV Import with zero manual mapping needed.
     * Imports whatever fields are available and sets safe defaults for missing ones.
     */
    public function autoImportCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:20480',
        ]);

        $file = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['csv', 'txt'])) {
            return response()->json([
                'status' => false,
                'message' => 'Please upload a valid .csv or .txt file.'
            ], 422);
        }

        $realPath = $file->getRealPath();
        $delimiter = $this->detectDelimiter($realPath);

        $handle = fopen($realPath, 'r');
        if (!$handle) {
            return response()->json([
                'status' => false,
                'message' => 'Could not read the uploaded CSV file.'
            ], 500);
        }

        $firstRow = fgetcsv($handle, 0, $delimiter, '"', "\\");
        if (!$firstRow) {
            fclose($handle);
            return response()->json([
                'status' => false,
                'message' => 'The uploaded file is empty.'
            ], 422);
        }

        if (isset($firstRow[0])) {
            $firstRow[0] = preg_replace('/^\xEF\xBB\xBF/', '', $firstRow[0]);
        }

        $headers = [];
        foreach ($firstRow as $idx => $colName) {
            $headers[$idx] = trim($colName);
        }

        // Automatic column detection by synonyms and exclusions
        $colName = $this->guessColumnIndex($headers, [
            'item name', 'product name', 'nama produk', 'dish name', 'food item', 'menu item',
            'name', 'nama', 'item', 'product', 'dish', 'particulars', 'title', 'description'
        ], ['restaurant', 'category', 'kategori', 'brand', 'merk', 'vendor', 'customer', 'user', 'id', 'code']);

        $colSellingPrice = $this->guessColumnIndex($headers, [
            'selling price', 'sale price', 'retail price', 'unit price', 'selling', 'sale', 
            'retail', 'price', 'rate', 'mrp', 'amount', 'harga jual', 'harga'
        ], ['id', 'uuid', 'code', 'barcode', 'hash', 'date', 'version', 'snapshot', 'serving', 'calories', 'fat', 'sodium', 'carb', 'protein', 'sugar', 'cholesterol', 'cost', 'buy', 'purchase']);

        $colCostPrice = $this->guessColumnIndex($headers, [
            'cost price', 'purchase price', 'buying price', 'cost', 
            'purchase rate', 'purchase', 'wholesale', 'wholesale cost', 'buy price', 'cogs', 'harga beli'
        ], ['id', 'code', 'selling', 'sale', 'retail']);

        $colCategory = $this->guessColumnIndex($headers, [
            'category name', 'category', 'kategori', 'cat', 'department', 'dept', 'group', 
            'item group', 'type', 'section', 'menu'
        ], ['id', 'code', 'name', 'price']);

        $colBrand = $this->guessColumnIndex($headers, [
            'brand', 'merk', 'restaurant name', 'restaurant', 'manufacturer', 'company', 'make'
        ], ['id', 'code']);

        $colStock = $this->guessColumnIndex($headers, [
            'stock', 'qty', 'quantity', 'inventory', 'balance', 'opening stock', 'units', 'count', 'stok'
        ], ['id', 'code', 'price']);

        $colCode = $this->guessColumnIndex($headers, [
            'barcode', 'upc', 'sku', 'product code', 'item code', 'kode produk', 'kode'
        ], ['version', 'snapshot', 'name', 'price', 'restaurant', 'item id', 'id']);

        $colDiscount = $this->guessColumnIndex($headers, [
            'discount', 'diskon', 'disc', 'rebate'
        ], ['id', 'code', 'price']);

        // If product name header was not detected, analyze data rows
        if ($colName === '') {
            $colName = $this->detectNameColumnFromSample($realPath, $delimiter, count($headers));
        }

        // NOTE: We do NOT force colSellingPrice, colCostPrice, colStock to arbitrary columns!
        // If absent, they will safely default to 0 so the user can enter/update them later.

        $defaultCategoryId = (int)$request->input('default_id_kategori');
        $validCategory = $defaultCategoryId > 0 ? Kategori::find($defaultCategoryId) : null;
        if (!$validCategory) {
            $validCategory = Kategori::first();
            if (!$validCategory) {
                $validCategory = Kategori::create(['nama_kategori' => 'General']);
            }
            $defaultCategoryId = $validCategory->id_kategori;
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $categoriesCreated = 0;

        $catCache = Kategori::all()->keyBy(function($item) {
            return strtolower(trim($item->nama_kategori));
        });

        $latest = Produk::latest('id_produk')->first();
        $codeCounter = $latest ? (int)$latest->id_produk : 0;

        $sessionCodes = [];

        $rowNum = 1;
        while (($row = fgetcsv($handle, 0, $delimiter, '"', "\\")) !== false) {
            $rowNum++;
            if (empty(array_filter($row, function($v) { return trim($v) !== ''; }))) {
                continue;
            }

            try {
                $name = isset($row[$colName]) ? trim($row[$colName]) : '';
                $name = trim($name, " \t\n\r\0\x0B\"'");
                $name = mb_convert_encoding($name, 'UTF-8', 'UTF-8');

                if ($name === '') {
                    $skipped++;
                    continue;
                }

                if (mb_strlen($name) > 190) {
                    $name = mb_substr($name, 0, 190);
                }

                $sellingPrice = ($colSellingPrice !== '' && isset($row[$colSellingPrice]))
                    ? $this->cleanNumeric($row[$colSellingPrice], 0)
                    : 0;

                $costPrice = ($colCostPrice !== '' && isset($row[$colCostPrice]))
                    ? $this->cleanNumeric($row[$colCostPrice], 0)
                    : 0;

                $stock = ($colStock !== '' && isset($row[$colStock]))
                    ? $this->cleanNumeric($row[$colStock], 0)
                    : 0;

                $discount = ($colDiscount !== '' && isset($row[$colDiscount]))
                    ? $this->cleanNumeric($row[$colDiscount], 0)
                    : 0;

                $brand = ($colBrand !== '' && isset($row[$colBrand]))
                    ? trim($row[$colBrand], " \t\n\r\0\x0B\"'")
                    : null;
                if ($brand !== null && $brand !== '') {
                    $brand = mb_substr(mb_convert_encoding($brand, 'UTF-8', 'UTF-8'), 0, 190);
                } else {
                    $brand = null;
                }

                // Category Resolution
                $categoryId = $defaultCategoryId;
                if ($colCategory !== '' && isset($row[$colCategory])) {
                    $catVal = trim($row[$colCategory], " \t\n\r\0\x0B\"'");
                    if ($catVal !== '') {
                        $catVal = mb_substr(mb_convert_encoding($catVal, 'UTF-8', 'UTF-8'), 0, 190);
                        $catKey = strtolower($catVal);
                        if (isset($catCache[$catKey])) {
                            $categoryId = $catCache[$catKey]->id_kategori;
                        } else {
                            $newCat = Kategori::create(['nama_kategori' => $catVal]);
                            $catCache[$catKey] = $newCat;
                            $categoryId = $newCat->id_kategori;
                            $categoriesCreated++;
                        }
                    }
                }

                // Barcode / Code Resolution
                $rawCode = ($colCode !== '' && isset($row[$colCode])) ? trim($row[$colCode], " \t\n\r\0\x0B\"'") : '';
                $productCode = '';

                if (
                    $rawCode !== '' &&
                    strlen($rawCode) <= 25 &&
                    !preg_match('/[eE][+-]?\d+/', $rawCode) &&
                    !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-/i', $rawCode) &&
                    preg_match('/^[a-zA-Z0-9_\-]+$/', $rawCode)
                ) {
                    $productCode = $rawCode;
                }

                if (
                    $productCode === '' ||
                    isset($sessionCodes[$productCode]) ||
                    Produk::where('kode_produk', $productCode)->where('nama_produk', '!=', $name)->exists()
                ) {
                    do {
                        $codeCounter++;
                        $productCode = 'P' . tambah_nol_didepan($codeCounter, 6);
                    } while (
                        isset($sessionCodes[$productCode]) ||
                        Produk::where('kode_produk', $productCode)->exists()
                    );
                }

                $sessionCodes[$productCode] = true;

                $existingProduct = Produk::where('nama_produk', $name)
                    ->orWhere(function($query) use ($productCode) {
                        if ($productCode !== '') {
                            $query->where('kode_produk', $productCode);
                        }
                    })
                    ->first();

                if ($existingProduct) {
                    $updateData = [
                        'id_kategori' => $categoryId,
                    ];
                    if ($sellingPrice > 0 || $existingProduct->harga_jual == 0) {
                        $updateData['harga_jual'] = $sellingPrice;
                    }
                    if ($costPrice > 0 || $existingProduct->harga_beli == 0) {
                        $updateData['harga_beli'] = $costPrice;
                    }
                    if ($stock > 0 || $existingProduct->stok == 0) {
                        $updateData['stok'] = $stock;
                    }
                    if ($brand !== null && $brand !== '') {
                        $updateData['merk'] = $brand;
                    }
                    if ($discount > 0) {
                        $updateData['diskon'] = $discount;
                    }

                    $existingProduct->update($updateData);
                    $updated++;
                } else {
                    Produk::create([
                        'id_kategori' => $categoryId,
                        'nama_produk' => $name,
                        'kode_produk' => $productCode,
                        'merk' => $brand,
                        'harga_beli' => $costPrice,
                        'harga_jual' => $sellingPrice,
                        'diskon' => $discount,
                        'stok' => $stock,
                    ]);
                    $imported++;
                }
            } catch (\Throwable $rowEx) {
                $skipped++;
                continue;
            }
        }

        fclose($handle);

        $msg = "Import completed! {$imported} new items added, {$updated} updated.";
        if ($categoriesCreated > 0) {
            $msg .= " ({$categoriesCreated} new categories created).";
        }
        if ($skipped > 0) {
            $msg .= " ({$skipped} empty rows skipped).";
        }

        return response()->json([
            'status' => true,
            'message' => $msg,
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'categories_created' => $categoriesCreated,
            'total' => ($imported + $updated)
        ]);
    }

    /**
     * Download sample CSV template.
     */
    public function downloadSampleCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="sample_menu_items.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Item Name', 'Category', 'Selling Price', 'Cost Price', 'Stock', 'Brand', 'Barcode', 'Discount']);
            fputcsv($handle, ['Chicken Biryani', 'Rice Dishes', '350', '220', '50', 'Chef Special', 'BC-1001', '0']);
            fputcsv($handle, ['Zinger Burger', 'Fast Food', '450', '270', '30', 'Royal', 'BC-1002', '5']);
            fputcsv($handle, ['Special Chai', 'Tea & Beverages', '120', '50', '100', '', 'BC-1003', '0']);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Parse uploaded CSV file, extract headers & sample preview, and auto-detect columns.
     */
    public function parseCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:10240',
        ]);

        $file = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['csv', 'txt'])) {
            return response()->json([
                'status' => false,
                'message' => 'Please upload a valid .csv or .txt file.'
            ], 422);
        }

        $realPath = $file->getRealPath();
        $delimiter = $this->detectDelimiter($realPath);

        $tempDir = storage_path('app/temp_imports');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $tempFileName = 'import_' . time() . '_' . uniqid() . '.csv';
        $tempPath = $tempDir . '/' . $tempFileName;
        copy($realPath, $tempPath);

        $handle = fopen($tempPath, 'r');
        if (!$handle) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to read the uploaded CSV file.'
            ], 500);
        }

        $firstRow = fgetcsv($handle, 0, $delimiter);
        if (!$firstRow) {
            fclose($handle);
            @unlink($tempPath);
            return response()->json([
                'status' => false,
                'message' => 'The uploaded file is empty.'
            ], 422);
        }

        // Clean UTF-8 BOM if present on first header
        if (isset($firstRow[0])) {
            $firstRow[0] = preg_replace('/^\xEF\xBB\xBF/', '', $firstRow[0]);
        }

        $headers = [];
        foreach ($firstRow as $idx => $colName) {
            $colClean = trim($colName);
            $headers[$idx] = $colClean ?: ('Column ' . ($idx + 1));
        }

        $previewRows = [];
        $totalRows = 0;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (empty(array_filter($row, function($v) { return trim($v) !== ''; }))) {
                continue;
            }
            $totalRows++;
            if (count($previewRows) < 4) {
                $paddedRow = [];
                foreach ($headers as $idx => $hName) {
                    $paddedRow[$idx] = isset($row[$idx]) ? trim($row[$idx]) : '';
                }
                $previewRows[] = $paddedRow;
            }
        }
        fclose($handle);

        if ($totalRows === 0) {
            @unlink($tempPath);
            return response()->json([
                'status' => false,
                'message' => 'The CSV file contains headers but no data rows.'
            ], 422);
        }

        // Smart column guesses
        $guesses = [
            'nama_produk' => $this->guessColumnIndex($headers, ['name', 'nama', 'item', 'item name', 'product', 'product name', 'dish', 'dish name', 'title', 'description', 'particulars']),
            'harga_jual' => $this->guessColumnIndex($headers, ['selling price', 'sale price', 'selling', 'sale rate', 'sale', 'retail price', 'retail', 'price', 'rate', 'mrp', 'unit price']),
            'harga_beli' => $this->guessColumnIndex($headers, ['cost price', 'purchase price', 'buying price', 'cost', 'purchase rate', 'purchase', 'wholesale', 'buy price', 'cogs']),
            'stok' => $this->guessColumnIndex($headers, ['stock', 'qty', 'quantity', 'inventory', 'balance', 'opening stock', 'units', 'count']),
            'kategori' => $this->guessColumnIndex($headers, ['category', 'kategori', 'cat', 'department', 'dept', 'group', 'item group', 'type']),
            'merk' => $this->guessColumnIndex($headers, ['brand', 'merk', 'manufacturer', 'company', 'make']),
            'kode_produk' => $this->guessColumnIndex($headers, ['code', 'item code', 'product code', 'barcode', 'sku', 'kode', 'upc', 'item no']),
            'diskon' => $this->guessColumnIndex($headers, ['discount', 'diskon', 'disc', 'rebate', 'discount %'])
        ];

        $categories = Kategori::all(['id_kategori', 'nama_kategori']);

        return response()->json([
            'status' => true,
            'temp_filename' => $tempFileName,
            'headers' => $headers,
            'preview_rows' => $previewRows,
            'total_rows' => $totalRows,
            'delimiter' => $delimiter,
            'guesses' => $guesses,
            'categories' => $categories
        ]);
    }

    /**
     * Process and import CSV rows according to mapped columns.
     */
    public function processCsv(Request $request)
    {
        $tempFileName = $request->input('temp_filename');
        $tempPath = storage_path('app/temp_imports/' . $tempFileName);

        if (!$tempFileName || !file_exists($tempPath)) {
            return response()->json([
                'status' => false,
                'message' => 'Import session expired or file not found. Please upload again.'
            ], 422);
        }

        $colName = $request->input('col_nama_produk');
        $colSellingPrice = $request->input('col_harga_jual');

        if ($colName === null || $colName === '' || $colSellingPrice === null || $colSellingPrice === '') {
            return response()->json([
                'status' => false,
                'message' => 'Item Name and Selling Price columns must be mapped.'
            ], 422);
        }

        $colCostPrice = $request->input('col_harga_beli');
        $colStock = $request->input('col_stok');
        $colCategory = $request->input('col_kategori');
        $colBrand = $request->input('col_merk');
        $colCode = $request->input('col_kode_produk');
        $colDiscount = $request->input('col_diskon');

        $defaultCategoryId = $request->input('default_id_kategori');
        $autoCreateCategory = $request->boolean('auto_create_kategori', true);
        $duplicateAction = $request->input('duplicate_action', 'update');

        if (!$defaultCategoryId) {
            $fallbackCat = Kategori::first();
            if (!$fallbackCat) {
                $fallbackCat = Kategori::create(['nama_kategori' => 'General']);
            }
            $defaultCategoryId = $fallbackCat->id_kategori;
        }

        $delimiter = $this->detectDelimiter($tempPath);
        $handle = fopen($tempPath, 'r');
        if (!$handle) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to process import file.'
            ], 500);
        }

        // Skip headers row
        fgetcsv($handle, 0, $delimiter);

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        $catCache = Kategori::all()->keyBy(function($item) {
            return strtolower(trim($item->nama_kategori));
        });

        $latest = Produk::latest('id_produk')->first();
        $codeCounter = $latest ? (int)$latest->id_produk : 0;

        DB::beginTransaction();
        try {
            $rowNum = 1;
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowNum++;
                if (empty(array_filter($row, function($v) { return trim($v) !== ''; }))) {
                    continue;
                }

                $name = isset($row[$colName]) ? trim($row[$colName]) : '';
                if ($name === '') {
                    $skipped++;
                    continue;
                }

                $sellingPrice = isset($row[$colSellingPrice]) ? $this->cleanNumeric($row[$colSellingPrice], 0) : 0;
                $costPrice = ($colCostPrice !== null && $colCostPrice !== '' && isset($row[$colCostPrice])) 
                    ? $this->cleanNumeric($row[$colCostPrice], 0) 
                    : 0;
                $stock = ($colStock !== null && $colStock !== '' && isset($row[$colStock])) 
                    ? $this->cleanNumeric($row[$colStock], 0) 
                    : 0;
                $discount = ($colDiscount !== null && $colDiscount !== '' && isset($row[$colDiscount])) 
                    ? $this->cleanNumeric($row[$colDiscount], 0) 
                    : 0;
                $brand = ($colBrand !== null && $colBrand !== '' && isset($row[$colBrand])) 
                    ? trim($row[$colBrand]) 
                    : null;

                // Category Resolution
                $categoryId = $defaultCategoryId;
                if ($colCategory !== null && $colCategory !== '' && isset($row[$colCategory])) {
                    $catVal = trim($row[$colCategory]);
                    if ($catVal !== '') {
                        $catKey = strtolower($catVal);
                        if (isset($catCache[$catKey])) {
                            $categoryId = $catCache[$catKey]->id_kategori;
                        } elseif ($autoCreateCategory) {
                            $newCat = Kategori::create(['nama_kategori' => $catVal]);
                            $catCache[$catKey] = $newCat;
                            $categoryId = $newCat->id_kategori;
                        }
                    }
                }

                // Barcode / Code Resolution
                $productCode = '';
                if ($colCode !== null && $colCode !== '' && isset($row[$colCode])) {
                    $productCode = trim($row[$colCode]);
                }

                if ($productCode === '') {
                    $codeCounter++;
                    $productCode = 'P' . tambah_nol_didepan($codeCounter, 6);
                    while (Produk::where('kode_produk', $productCode)->exists()) {
                        $codeCounter++;
                        $productCode = 'P' . tambah_nol_didepan($codeCounter, 6);
                    }
                }

                // Check for existing product
                $existingProduct = Produk::where('nama_produk', $name)
                    ->orWhere(function($query) use ($productCode) {
                        if ($productCode !== '') {
                            $query->where('kode_produk', $productCode);
                        }
                    })
                    ->first();

                if ($existingProduct) {
                    if ($duplicateAction === 'skip') {
                        $skipped++;
                        continue;
                    } else {
                        $updateData = [
                            'id_kategori' => $categoryId,
                            'harga_jual' => (int)$sellingPrice,
                        ];
                        if ($costPrice > 0 || $existingProduct->harga_beli == 0) {
                            $updateData['harga_beli'] = (int)$costPrice;
                        }
                        if ($stock > 0 || $existingProduct->stok == 0) {
                            $updateData['stok'] = (int)$stock;
                        }
                        if ($brand !== null && $brand !== '') {
                            $updateData['merk'] = $brand;
                        }
                        if ($discount > 0) {
                            $updateData['diskon'] = (int)$discount;
                        }

                        $existingProduct->update($updateData);
                        $updated++;
                    }
                } else {
                    Produk::create([
                        'id_kategori' => $categoryId,
                        'nama_produk' => $name,
                        'kode_produk' => $productCode,
                        'merk' => $brand,
                        'harga_beli' => (int)$costPrice,
                        'harga_jual' => (int)$sellingPrice,
                        'diskon' => (int)$discount,
                        'stok' => (int)$stock,
                    ]);
                    $imported++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($handle);
            @unlink($tempPath);
            return response()->json([
                'status' => false,
                'message' => 'Import error on line ' . ($rowNum ?? 'unknown') . ': ' . $e->getMessage()
            ], 500);
        }

        fclose($handle);
        @unlink($tempPath);

        return response()->json([
            'status' => true,
            'message' => 'Items imported successfully!',
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'total' => ($imported + $updated + $skipped)
        ]);
    }

    private function detectDelimiter($filePath)
    {
        $delimiters = [',', ';', "\t", '|'];
        $firstLine = '';
        $handle = @fopen($filePath, 'r');
        if ($handle) {
            $firstLine = fgets($handle);
            fclose($handle);
        }
        if (!$firstLine) return ',';

        $bestDelimiter = ',';
        $maxCount = 0;
        foreach ($delimiters as $delim) {
            $count = substr_count($firstLine, $delim);
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelimiter = $delim;
            }
        }
        return $bestDelimiter;
    }

    private function guessColumnIndex(array $headers, array $synonyms, array $exclude = [])
    {
        // Phase 1: Try exact match or multi-word phrase match first (highest specificity)
        foreach ($synonyms as $synonym) {
            $synClean = strtolower(trim($synonym));
            foreach ($headers as $index => $header) {
                $cleaned = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', ' ', $header)));
                $cleanWords = array_filter(explode(' ', $cleaned));

                // Exclude specified conflicting words
                $hasExcluded = false;
                foreach ($exclude as $ex) {
                    if (in_array(strtolower($ex), $cleanWords)) {
                        $hasExcluded = true;
                        break;
                    }
                }
                if ($hasExcluded) continue;

                // Exact match
                if ($cleaned === $synClean) {
                    return (string)$index;
                }
                // Multi-word phrase match
                if (strpos($synClean, ' ') !== false && strpos($cleaned, $synClean) !== false) {
                    return (string)$index;
                }
            }
        }

        // Phase 2: Single-word token match (only for single-word synonyms)
        foreach ($synonyms as $synonym) {
            $synClean = strtolower(trim($synonym));
            if (strpos($synClean, ' ') !== false) continue;

            foreach ($headers as $index => $header) {
                $cleaned = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', ' ', $header)));
                $cleanWords = array_filter(explode(' ', $cleaned));

                $hasExcluded = false;
                foreach ($exclude as $ex) {
                    if (in_array(strtolower($ex), $cleanWords)) {
                        $hasExcluded = true;
                        break;
                    }
                }
                if ($hasExcluded) continue;

                if (in_array($synClean, $cleanWords)) {
                    return (string)$index;
                }
            }
        }

        return '';
    }

    private function detectNameColumnFromSample($filePath, $delimiter, $totalCols)
    {
        $handle = @fopen($filePath, 'r');
        if (!$handle) return '0';

        // Skip headers
        fgetcsv($handle, 0, $delimiter, '"', "\\");

        $score = array_fill(0, max(1, $totalCols), 0);
        $rowCount = 0;

        while (($row = fgetcsv($handle, 0, $delimiter, '"', "\\")) !== false && $rowCount < 10) {
            $rowCount++;
            foreach ($row as $idx => $val) {
                if ($idx >= $totalCols) continue;
                $v = trim((string)$val);
                // Product name characteristics: contains letters, length 2-120, not JSON/URL/Date/UUID
                if (
                    preg_match('/[a-zA-Z]{2,}/', $v) &&
                    strlen($v) >= 2 &&
                    strlen($v) <= 120 &&
                    !preg_match('/^(\{|\[|http|\d{4}-\d{2}-\d{2}|[0-9a-f]{8}-[0-9a-f]{4}-)/i', $v)
                ) {
                    $score[$idx]++;
                }
            }
        }
        fclose($handle);

        arsort($score);
        $bestIdx = key($score);
        return ($score[$bestIdx] > 0) ? (string)$bestIdx : '0';
    }

    private function cleanNumeric($value, $default = 0)
    {
        if ($value === null || $value === '') return $default;

        $str = trim((string)$value);

        // Discard scientific notation like 3.43E+39, long hashes or UUIDs
        if (preg_match('/[eE][+-]?\d+/', $str) || strlen($str) > 20) {
            return $default;
        }

        // 1. Remove alphabetic characters (e.g. "Rs", "PKR", "USD")
        $val = preg_replace('/[a-zA-Z]/', '', $str);
        // 2. Remove stray leading dot if caused by abbreviations like "Rs."
        $val = preg_replace('/^\s*\.\s*/', '', $val);
        // 3. Remove non-numeric characters except dots and commas
        $val = preg_replace('/[^\d.,]/', '', $val);
        // 4. Remove comma thousands separators
        $val = str_replace(',', '', $val);

        if ($val === '' || !is_numeric($val)) {
            return $default;
        }

        $f = (float)$val;
        // Limit to valid integer range (0 to 100,000,000)
        if ($f < 0 || $f > 100000000 || is_nan($f) || is_infinite($f)) {
            return $default;
        }

        return (int)round($f);
    }
}

