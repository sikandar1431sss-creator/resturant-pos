<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusAndDueToPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan', 'status_pembayaran')) {
                $table->string('status_pembayaran', 20)->default('paid')->after('diterima');
            }
            if (!Schema::hasColumn('penjualan', 'sisa_bayar')) {
                $table->double('sisa_bayar')->default(0)->after('status_pembayaran');
            }
            if (!Schema::hasColumn('penjualan', 'jatuh_tempo')) {
                $table->date('jatuh_tempo')->nullable()->after('sisa_bayar');
            }
            if (!Schema::hasColumn('penjualan', 'metode_pembayaran')) {
                $table->string('metode_pembayaran', 30)->default('cash')->after('jatuh_tempo');
            }
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
            $table->dropColumn(['status_pembayaran', 'sisa_bayar', 'jatuh_tempo', 'metode_pembayaran']);
        });
    }
}
