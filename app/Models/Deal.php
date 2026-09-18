<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $table = 'deals';
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(DealItem::class, 'id_deal', 'id')->with('produk');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    /**
     * Calculate estimated food cost of the deal based on included items' cost prices
     */
    public function calculateEstimatedCost()
    {
        $cost = 0;
        foreach ($this->items as $item) {
            if ($item->produk) {
                $cost += ($item->produk->harga_beli * $item->jumlah);
            }
        }
        return $cost;
    }
}
