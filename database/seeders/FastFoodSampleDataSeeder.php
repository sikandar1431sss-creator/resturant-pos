<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\RawMaterial;
use App\Models\ProductRecipe;
use Illuminate\Database\Seeder;

class FastFoodSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Sample Raw Materials
        $materials = [
            ['kode_material' => 'RAW-0001', 'nama_material' => 'Burger Buns (Sesame)', 'satuan' => 'Pcs', 'stok' => 150, 'min_stok' => 20, 'harga_beli' => 35],
            ['kode_material' => 'RAW-0002', 'nama_material' => 'Zinger Chicken Fillet', 'satuan' => 'Pcs', 'stok' => 120, 'min_stok' => 15, 'harga_beli' => 180],
            ['kode_material' => 'RAW-0003', 'nama_material' => 'Beef Patty (100g)', 'satuan' => 'Pcs', 'stok' => 80, 'min_stok' => 10, 'harga_beli' => 220],
            ['kode_material' => 'RAW-0004', 'nama_material' => 'Cheddar Cheese Slices', 'satuan' => 'Pcs', 'stok' => 200, 'min_stok' => 25, 'harga_beli' => 45],
            ['kode_material' => 'RAW-0005', 'nama_material' => 'Garlic Mayo Sauce', 'satuan' => 'Grams', 'stok' => 5000, 'min_stok' => 500, 'harga_beli' => 0.5],
            ['kode_material' => 'RAW-0006', 'nama_material' => 'Spicy Chipotle Sauce', 'satuan' => 'Grams', 'stok' => 4000, 'min_stok' => 400, 'harga_beli' => 0.6],
            ['kode_material' => 'RAW-0007', 'nama_material' => 'Frozen French Fries (Potatoes)', 'satuan' => 'Kg', 'stok' => 40, 'min_stok' => 5, 'harga_beli' => 350],
            ['kode_material' => 'RAW-0008', 'nama_material' => 'Mozzarella Cheese (Grated)', 'satuan' => 'Kg', 'stok' => 15, 'min_stok' => 3, 'harga_beli' => 1200],
            ['kode_material' => 'RAW-0009', 'nama_material' => 'Pizza Dough Base (Medium)', 'satuan' => 'Pcs', 'stok' => 50, 'min_stok' => 10, 'harga_beli' => 90],
            ['kode_material' => 'RAW-0010', 'nama_material' => 'Coffee Beans (Espresso Blend)', 'satuan' => 'Grams', 'stok' => 3000, 'min_stok' => 500, 'harga_beli' => 3.5],
            ['kode_material' => 'RAW-0011', 'nama_material' => 'Fresh Milk Pack (1L)', 'satuan' => 'Liters', 'stok' => 25, 'min_stok' => 5, 'harga_beli' => 240],
            ['kode_material' => 'RAW-0012', 'nama_material' => 'Coke Can (250ml)', 'satuan' => 'Cans', 'stok' => 96, 'min_stok' => 24, 'harga_beli' => 70],
            ['kode_material' => 'RAW-0013', 'nama_material' => 'Burger Packaging Box', 'satuan' => 'Boxes', 'stok' => 300, 'min_stok' => 50, 'harga_beli' => 12],
            ['kode_material' => 'RAW-0014', 'nama_material' => 'Disposable Drink Cup with Lid', 'satuan' => 'Pcs', 'stok' => 250, 'min_stok' => 40, 'harga_beli' => 15],
        ];

        foreach ($materials as $m) {
            RawMaterial::firstOrCreate(['kode_material' => $m['kode_material']], $m);
        }

        // 2. Link Recipes to existing dishes if any products match
        $allProducts = Produk::all();
        $bun = RawMaterial::where('kode_material', 'RAW-0001')->first();
        $fillet = RawMaterial::where('kode_material', 'RAW-0002')->first();
        $cheese = RawMaterial::where('kode_material', 'RAW-0004')->first();
        $mayo = RawMaterial::where('kode_material', 'RAW-0005')->first();
        $box = RawMaterial::where('kode_material', 'RAW-0013')->first();

        foreach ($allProducts as $p) {
            $name = strtolower($p->nama_produk);
            if (str_contains($name, 'burger') || str_contains($name, 'zinger')) {
                if ($p->recipes()->count() == 0) {
                    if ($bun) ProductRecipe::create(['id_produk' => $p->id_produk, 'id_raw_material' => $bun->id, 'jumlah' => 1]);
                    if ($fillet) ProductRecipe::create(['id_produk' => $p->id_produk, 'id_raw_material' => $fillet->id, 'jumlah' => 1]);
                    if ($cheese) ProductRecipe::create(['id_produk' => $p->id_produk, 'id_raw_material' => $cheese->id, 'jumlah' => 1]);
                    if ($mayo) ProductRecipe::create(['id_produk' => $p->id_produk, 'id_raw_material' => $mayo->id, 'jumlah' => 25]);
                    if ($box) ProductRecipe::create(['id_produk' => $p->id_produk, 'id_raw_material' => $box->id, 'jumlah' => 1]);
                }
            }
        }
    }
}
