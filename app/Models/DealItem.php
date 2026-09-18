<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealItem extends Model
{
    use HasFactory;

    protected $table = 'deal_items';
    protected $guarded = [];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'id_deal', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk')->with('recipes.rawMaterial');
    }
}
