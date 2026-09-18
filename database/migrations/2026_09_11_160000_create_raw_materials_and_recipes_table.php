<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawMaterialsAndRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Raw Materials / Inventory Ingredients
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('kode_material')->unique();
            $table->string('nama_material');
            $table->string('satuan')->default('Pcs'); // Pcs, Grams, Kg, ML, Liter, Can, Box
            $table->double('stok')->default(0);
            $table->double('min_stok')->default(5);
            $table->double('harga_beli')->default(0);
            $table->timestamps();
        });

        // 2. Product Recipes / Bill of Materials (BOM)
        Schema::create('resep_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_produk');
            $table->unsignedBigInteger('id_raw_material');
            $table->double('jumlah')->default(1); // quantity of ingredient used per 1 dish/burger
            $table->timestamps();

            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produk')
                  ->onDelete('cascade');

            $table->foreign('id_raw_material')
                  ->references('id')
                  ->on('raw_materials')
                  ->onDelete('cascade');
        });

        // 3. Daily Shift Closings (Z-Report / Cash Drawer Settlement)
        Schema::create('shift_closings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->double('opening_cash')->default(0);
            $table->double('total_cash_sales')->default(0);
            $table->double('total_card_sales')->default(0);
            $table->double('total_online_sales')->default(0);
            $table->double('total_expense')->default(0);
            $table->double('expected_cash')->default(0);
            $table->double('actual_cash')->default(0);
            $table->double('difference')->default(0);
            $table->text('catatan')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();

            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_closings');
        Schema::dropIfExists('resep_produk');
        Schema::dropIfExists('raw_materials');
    }
}
