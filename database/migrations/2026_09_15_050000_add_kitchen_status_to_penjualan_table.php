<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKitchenStatusToPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan', 'kitchen_status')) {
                $table->string('kitchen_status', 20)->default('pending')->after('status_pembayaran');
            }
            if (!Schema::hasColumn('penjualan', 'kitchen_started_at')) {
                $table->timestamp('kitchen_started_at')->nullable()->after('kitchen_status');
            }
            if (!Schema::hasColumn('penjualan', 'kitchen_ready_at')) {
                $table->timestamp('kitchen_ready_at')->nullable()->after('kitchen_started_at');
            }
            if (!Schema::hasColumn('penjualan', 'kitchen_served_at')) {
                $table->timestamp('kitchen_served_at')->nullable()->after('kitchen_ready_at');
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
            $table->dropColumn([
                'kitchen_status',
                'kitchen_started_at',
                'kitchen_ready_at',
                'kitchen_served_at'
            ]);
        });
    }
}
