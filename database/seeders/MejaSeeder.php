<?php

namespace Database\Seeders;

use App\Models\Meja;
use Illuminate\Database\Seeder;

class MejaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tables = [
            ['nomor_meja' => 'Table 1', 'kapasitas' => 2, 'status' => 'available'],
            ['nomor_meja' => 'Table 2', 'kapasitas' => 4, 'status' => 'available'],
            ['nomor_meja' => 'Table 3', 'kapasitas' => 4, 'status' => 'available'],
            ['nomor_meja' => 'Table 4', 'kapasitas' => 6, 'status' => 'available'],
            ['nomor_meja' => 'Table 5', 'kapasitas' => 6, 'status' => 'available'],
            ['nomor_meja' => 'Table 6', 'kapasitas' => 4, 'status' => 'available'],
            ['nomor_meja' => 'Table 7', 'kapasitas' => 2, 'status' => 'available'],
            ['nomor_meja' => 'Table 8', 'kapasitas' => 8, 'status' => 'available'],
            ['nomor_meja' => 'VIP Table 1', 'kapasitas' => 8, 'status' => 'available'],
            ['nomor_meja' => 'VIP Table 2', 'kapasitas' => 10, 'status' => 'available'],
            ['nomor_meja' => 'Outdoor 1', 'kapasitas' => 4, 'status' => 'available'],
            ['nomor_meja' => 'Outdoor 2', 'kapasitas' => 4, 'status' => 'available'],
        ];

        foreach ($tables as $t) {
            Meja::updateOrCreate(
                ['nomor_meja' => $t['nomor_meja']],
                ['kapasitas' => $t['kapasitas'], 'status' => $t['status']]
            );
        }
    }
}
