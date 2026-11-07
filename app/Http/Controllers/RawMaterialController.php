<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * Download sample CSV template for raw materials.
     */
    public function downloadSampleCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="sample_raw_materials.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Code', 'Material Name', 'Unit', 'Current Stock', 'Min Stock Alert', 'Cost Price']);
            fputcsv($handle, ['RAW-0001', 'Chicken Breast Fillet', 'kg', '25.5', '5', '850']);
            fputcsv($handle, ['RAW-0002', 'Cooking Oil', 'liter', '50', '10', '480']);
            fputcsv($handle, ['RAW-0003', 'Burger Buns', 'piece', '120', '20', '35']);
            fputcsv($handle, ['RAW-0004', 'Cheddar Cheese Slice', 'pack', '15', '3', '1250']);
            fputcsv($handle, ['RAW-0005', 'Fresh Tomato', 'kg', '12', '2', '120']);
            fputcsv($handle, ['RAW-0006', 'Mayonnaise Sauce', 'bottle', '8', '2', '650']);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all raw materials to CSV file.
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="raw_materials_' . date('Y-m-d_His') . '.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Code', 'Material Name', 'Unit', 'Current Stock', 'Min Stock Alert', 'Cost Price']);

            $materials = RawMaterial::orderBy('id', 'asc')->get();
            foreach ($materials as $m) {
                fputcsv($handle, [
                    $m->kode_material,
                    $m->nama_material,
                    $m->satuan,
                    $m->stok,
                    $m->min_stok,
                    $m->harga_beli,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 1-Click Automatic CSV Import for Raw Materials.
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
            'material name', 'raw material name', 'raw material', 'raw item', 'ingredient name',
            'ingredient', 'nama material', 'nama bahan', 'bahan baku', 'item name', 'product name',
            'nama', 'name', 'material', 'ingredient', 'item', 'description', 'particulars'
        ], ['satuan', 'unit', 'kode', 'code', 'stock', 'stok', 'harga', 'cost', 'price', 'alert']);

        $colCode = $this->guessColumnIndex($headers, [
            'material code', 'raw code', 'kode material', 'kode bahan', 'item code', 'product code',
            'code', 'kode', 'sku', 'barcode'
        ], ['name', 'nama', 'unit', 'satuan', 'price', 'cost', 'stock']);

        $colUnit = $this->guessColumnIndex($headers, [
            'unit of measure', 'unit of measurement', 'measurement unit', 'uom', 'satuan', 'unit', 'measure'
        ], ['cost', 'price', 'code', 'kode', 'name', 'nama']);

        $colStock = $this->guessColumnIndex($headers, [
            'current stock', 'stok saat ini', 'stock quantity', 'opening stock', 'inventory stock',
            'stock', 'stok', 'qty', 'quantity', 'balance', 'units', 'count'
        ], ['min', 'alert', 'minimum', 'code', 'kode', 'cost', 'price']);

        $colMinStock = $this->guessColumnIndex($headers, [
            'low stock alert', 'min stock alert', 'min stock', 'minimum stock', 'min alert', 'alert stock',
            'reorder level', 'reorder point', 'min stok', 'stok minimum', 'threshold', 'low stock', 'alert'
        ], ['current', 'opening', 'balance', 'price', 'cost']);

        $colCostPrice = $this->guessColumnIndex($headers, [
            'cost price', 'purchase price', 'buying price', 'cost per unit', 'unit cost',
            'purchase rate', 'cost', 'purchase', 'wholesale', 'buy price', 'harga beli', 'harga'
        ], ['selling', 'jual', 'retail', 'code', 'kode', 'stock', 'stok']);

        // If material name header was not detected, analyze sample rows
        if ($colName === '') {
            $colName = $this->detectNameColumnFromSample($realPath, $delimiter, count($headers));
        }

        $defaultUnit = strtolower(trim((string)$request->input('default_satuan', 'piece')));
        if ($defaultUnit === '') {
            $defaultUnit = 'piece';
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        $latest = RawMaterial::latest('id')->first();
        $codeCounter = $latest ? (int)$latest->id : 0;
        $sessionCodes = [];

        $unitMap = [
            'kilogram' => 'kg', 'kilograms' => 'kg', 'kgs' => 'kg', 'kilo' => 'kg',
            'grams' => 'gram', 'gm' => 'gram', 'gms' => 'gram', 'g' => 'gram',
            'liters' => 'liter', 'litres' => 'liter', 'litre' => 'liter', 'ltr' => 'liter', 'l' => 'liter',
            'milliliter' => 'ml', 'milliliters' => 'ml', 'millilitre' => 'ml',
            'pieces' => 'piece', 'pcs' => 'piece', 'pc' => 'piece', 'piece' => 'piece',
            'boxes' => 'box', 'box' => 'box',
            'packets' => 'pack', 'packs' => 'pack', 'pkt' => 'pack', 'pack' => 'pack',
            'bottles' => 'bottle', 'btl' => 'bottle', 'bottle' => 'bottle',
            'cans' => 'can', 'can' => 'can',
            'dozens' => 'dozen', 'doz' => 'dozen', 'dozen' => 'dozen',
        ];

        while (($row = fgetcsv($handle, 0, $delimiter, '"', "\\")) !== false) {
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

                // Unit resolution
                $unit = '';
                if ($colUnit !== '' && isset($row[$colUnit])) {
                    $rawUnit = strtolower(trim($row[$colUnit], " \t\n\r\0\x0B\"'"));
                    $unit = isset($unitMap[$rawUnit]) ? $unitMap[$rawUnit] : $rawUnit;
                }
                if ($unit === '') {
                    $unit = $defaultUnit;
                }
                if (strlen($unit) > 50) {
                    $unit = substr($unit, 0, 50);
                }

                // Numeric fields
                $stock = ($colStock !== '' && isset($row[$colStock]))
                    ? $this->cleanNumericDouble($row[$colStock], 0.0)
                    : 0.0;

                $minStock = ($colMinStock !== '' && isset($row[$colMinStock]))
                    ? $this->cleanNumericDouble($row[$colMinStock], 5.0)
                    : 5.0;

                $costPrice = ($colCostPrice !== '' && isset($row[$colCostPrice]))
                    ? $this->cleanNumericDouble($row[$colCostPrice], 0.0)
                    : 0.0;

                // Code resolution
                $rawCode = ($colCode !== '' && isset($row[$colCode])) ? trim($row[$colCode], " \t\n\r\0\x0B\"'") : '';
                $materialCode = '';

                if (
                    $rawCode !== '' &&
                    strlen($rawCode) <= 25 &&
                    !preg_match('/[eE][+-]?\d+/', $rawCode) &&
                    preg_match('/^[a-zA-Z0-9_\-]+$/', $rawCode)
                ) {
                    $materialCode = $rawCode;
                }

                if (
                    $materialCode === '' ||
                    isset($sessionCodes[$materialCode]) ||
                    RawMaterial::where('kode_material', $materialCode)->where('nama_material', '!=', $name)->exists()
                ) {
                    do {
                        $codeCounter++;
                        $materialCode = 'RAW-' . str_pad($codeCounter, 4, '0', STR_PAD_LEFT);
                    } while (
                        isset($sessionCodes[$materialCode]) ||
                        RawMaterial::where('kode_material', $materialCode)->exists()
                    );
                }

                $sessionCodes[$materialCode] = true;

                $existingMaterial = RawMaterial::where('nama_material', $name)
                    ->orWhere(function($query) use ($materialCode) {
                        if ($materialCode !== '') {
                            $query->where('kode_material', $materialCode);
                        }
                    })
                    ->first();

                if ($existingMaterial) {
                    $updateData = [];
                    if ($unit !== '') {
                        $updateData['satuan'] = $unit;
                    }
                    if ($stock > 0 || $existingMaterial->stok == 0) {
                        $updateData['stok'] = $stock;
                    }
                    if ($minStock > 0 || $existingMaterial->min_stok == 0) {
                        $updateData['min_stok'] = $minStock;
                    }
                    if ($costPrice > 0 || $existingMaterial->harga_beli == 0) {
                        $updateData['harga_beli'] = $costPrice;
                    }

                    if (!empty($updateData)) {
                        $existingMaterial->update($updateData);
                    }
                    $updated++;
                } else {
                    RawMaterial::create([
                        'kode_material' => $materialCode,
                        'nama_material' => $name,
                        'satuan' => $unit,
                        'stok' => $stock,
                        'min_stok' => $minStock > 0 ? $minStock : 5.0,
                        'harga_beli' => $costPrice,
                    ]);
                    $imported++;
                }
            } catch (\Throwable $rowEx) {
                $skipped++;
                continue;
            }
        }

        fclose($handle);

        $msg = "Import completed! {$imported} new raw materials added, {$updated} updated.";
        if ($skipped > 0) {
            $msg .= " ({$skipped} invalid/empty rows skipped).";
        }

        return response()->json([
            'status' => true,
            'message' => $msg,
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'total' => ($imported + $updated)
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
        // Phase 1: Exact or multi-word match
        foreach ($synonyms as $synonym) {
            $synClean = strtolower(trim($synonym));
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

                if ($cleaned === $synClean) {
                    return (string)$index;
                }
                if (strpos($synClean, ' ') !== false && strpos($cleaned, $synClean) !== false) {
                    return (string)$index;
                }
            }
        }

        // Phase 2: Single word token match
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

        fgetcsv($handle, 0, $delimiter, '"', "\\");

        $score = array_fill(0, max(1, $totalCols), 0);
        $rowCount = 0;

        while (($row = fgetcsv($handle, 0, $delimiter, '"', "\\")) !== false && $rowCount < 10) {
            $rowCount++;
            foreach ($row as $idx => $val) {
                if ($idx >= $totalCols) continue;
                $v = trim((string)$val);
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

    private function cleanNumericDouble($value, $default = 0.0)
    {
        if ($value === null || $value === '') return $default;

        $str = trim((string)$value);

        if (preg_match('/[eE][+-]?\d+/', $str) || strlen($str) > 20) {
            return $default;
        }

        $val = preg_replace('/[a-zA-Z]/', '', $str);
        $val = preg_replace('/^\s*\.\s*/', '', $val);
        $val = preg_replace('/[^\d.,]/', '', $val);
        $val = str_replace(',', '', $val);

        if ($val === '' || !is_numeric($val)) {
            return $default;
        }

        $f = (float)$val;
        if ($f < 0 || $f > 1000000000 || is_nan($f) || is_infinite($f)) {
            return $default;
        }

        return round($f, 4);
    }
}
