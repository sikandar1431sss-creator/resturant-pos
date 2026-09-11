<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderDetailsToPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->string('nomor_meja')->nullable()->after('id_member');
            $table->string('tipe_order')->nullable()->after('nomor_meja');
            $table->text('catatan')->nullable()->after('tipe_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['nomor_meja', 'tipe_order', 'catatan']);
        });
    }
}
