<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\RawMaterial;
use App\Models\ProductRecipe;
use Illuminate\Http\Request;

class ProductRecipeController extends Controller
{
    public function index()
    {
        $produk = Produk::with('recipes.rawMaterial', 'kategori')->orderBy('nama_produk', 'asc')->get();
        $rawMaterials = RawMaterial::orderBy('nama_material', 'asc')->get();

        return view('recipe.index', compact('produk', 'rawMaterials'));
    }

    public function getRecipe($id_produk)
    {
        $produk = Produk::with(['recipes.rawMaterial', 'kategori'])->findOrFail($id_produk);
        return response()->json([
            'produk' => $produk,
            'recipes' => $produk->recipes
        ]);
    }

    public function saveRecipe(Request $request, $id_produk)
    {
        $produk = Produk::findOrFail($id_produk);

        // Remove old recipe items
        ProductRecipe::where('id_produk', $produk->id_produk)->delete();

        if ($request->has('ingredients') && is_array($request->ingredients)) {
            foreach ($request->ingredients as $item) {
                if (!empty($item['id_raw_material']) && !empty($item['jumlah']) && $item['jumlah'] > 0) {
                    ProductRecipe::create([
                        'id_produk' => $produk->id_produk,
                        'id_raw_material' => $item['id_raw_material'],
                        'jumlah' => (float)$item['jumlah'],
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe saved successfully for ' . $produk->nama_produk
        ]);
    }
}
