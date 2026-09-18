<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMejaAndEnhancePenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Create meja (Tables) table
        if (!Schema::hasTable('meja')) {
            Schema::create('meja', function (Blueprint $table) {
                $table->increments('id_meja');
                $table->string('nomor_meja', 50)->unique();
                $table->integer('kapasitas')->default(4);
                $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
                $table->integer('id_penjualan_aktif')->nullable();
                $table->timestamps();
            });
        }

        // 2. Add catatan (item note) to penjualan_detail
        Schema::table('penjualan_detail', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan_detail', 'catatan')) {
                $table->text('catatan')->nullable()->after('subtotal');
            }
        });

        // 3. Add delivery & customer details to penjualan
        Schema::table('penjualan', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan', 'ongkir')) {
                $table->double('ongkir')->default(0)->after('diskon');
            }
            if (!Schema::hasColumn('penjualan', 'nama_pelanggan')) {
                $table->string('nama_pelanggan')->nullable()->after('id_member');
            }
            if (!Schema::hasColumn('penjualan', 'telepon_pelanggan')) {
                $table->string('telepon_pelanggan')->nullable()->after('nama_pelanggan');
            }
            if (!Schema::hasColumn('penjualan', 'alamat_pengiriman')) {
                $table->text('alamat_pengiriman')->nullable()->after('telepon_pelanggan');
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
        Schema::dropIfExists('meja');

        Schema::table('penjualan_detail', function (Blueprint $table) {
            if (Schema::hasColumn('penjualan_detail', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $cols = ['ongkir', 'nama_pelanggan', 'telepon_pelanggan', 'alamat_pengiriman'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('penjualan', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
