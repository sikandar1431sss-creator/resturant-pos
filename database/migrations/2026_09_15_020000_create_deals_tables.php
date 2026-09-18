<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('deals')) {
            Schema::create('deals', function (Blueprint $table) {
                $table->id();
                $table->string('kode_deal', 50)->unique();
                $table->string('nama_deal');
                $table->text('deskripsi')->nullable();
                $table->double('harga_jual')->default(0);
                $table->double('cost_price')->default(0);
                $table->string('foto')->nullable();
                $table->boolean('status')->default(1);
                $table->unsignedInteger('id_produk')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('deal_items')) {
            Schema::create('deal_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_deal');
                $table->unsignedInteger('id_produk');
                $table->integer('jumlah')->default(1);
                $table->timestamps();

                $table->foreign('id_deal')->references('id')->on('deals')->onDelete('cascade');
                $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_items');
        Schema::dropIfExists('deals');
    }
}
