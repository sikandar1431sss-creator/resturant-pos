<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';
    protected $primaryKey = 'id_meja';
    protected $guarded = [];

    public function penjualanAktif()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan_aktif', 'id_penjualan');
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isOccupied()
    {
        return $this->status === 'occupied';
    }

    /**
     * Synchronize table statuses with current active unpaid dine-in orders.
     */
    public static function syncStatuses()
    {
        $tables = self::all();
        $unpaidOrders = Penjualan::where('tipe_order', 'Dine-In')
            ->where(function($q) {
                $q->where('status_pembayaran', '!=', 'paid')
                  ->orWhere('diterima', '<', \Illuminate\Support\Facades\DB::raw('bayar'));
            })
            ->where('total_item', '>', 0)
            ->orderBy('id_penjualan', 'desc')
            ->get();

        $occupiedMap = [];
        foreach ($unpaidOrders as $ord) {
            $tableName = trim(strtolower($ord->nomor_meja ?? ''));
            if ($tableName && !isset($occupiedMap[$tableName])) {
                $occupiedMap[$tableName] = $ord;
            }
        }

        foreach ($tables as $t) {
            $key = trim(strtolower($t->nomor_meja));
            if (isset($occupiedMap[$key])) {
                $activeOrder = $occupiedMap[$key];
                $t->status = 'occupied';
                $t->id_penjualan_aktif = $activeOrder->id_penjualan;
            } else {
                $t->status = 'available';
                $t->id_penjualan_aktif = null;
            }
            $t->save();
        }

        return $tables;
    }
}

