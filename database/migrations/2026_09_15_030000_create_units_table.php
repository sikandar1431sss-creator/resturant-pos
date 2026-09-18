<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan')->unique(); // e.g. kg, gram, liter, ml, piece, box, pack, can, bottle, dozen
            $table->string('deskripsi')->nullable();  // e.g. Kilogram, Grams, Liters, Pieces
            $table->string('simbol')->nullable();     // e.g. kg, g, L, ml, pcs, box, pack, can, btl, doz
            $table->timestamps();
        });

        // Pre-seed default restaurant & retail measurement units
        $defaultUnits = [
            ['nama_satuan' => 'kg', 'deskripsi' => 'Kilogram (کلوگرام)', 'simbol' => 'kg'],
            ['nama_satuan' => 'gram', 'deskripsi' => 'Grams (گرام)', 'simbol' => 'g'],
            ['nama_satuan' => 'liter', 'deskripsi' => 'Liters (لیٹر)', 'simbol' => 'L'],
            ['nama_satuan' => 'ml', 'deskripsi' => 'Milliliters (ملی لیٹر)', 'simbol' => 'ml'],
            ['nama_satuan' => 'piece', 'deskripsi' => 'Pieces / Units (عدد)', 'simbol' => 'pcs'],
            ['nama_satuan' => 'box', 'deskripsi' => 'Box / Carton (ڈبہ / کارٹن)', 'simbol' => 'box'],
            ['nama_satuan' => 'pack', 'deskripsi' => 'Packet (پیکٹ)', 'simbol' => 'pack'],
            ['nama_satuan' => 'can', 'deskripsi' => 'Tin Can (کین)', 'simbol' => 'can'],
            ['nama_satuan' => 'bottle', 'deskripsi' => 'Bottle (بوتل)', 'simbol' => 'btl'],
            ['nama_satuan' => 'dozen', 'deskripsi' => 'Dozen (درجن)', 'simbol' => 'doz'],
        ];

        foreach ($defaultUnits as $u) {
            DB::table('units')->insert(array_merge($u, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
