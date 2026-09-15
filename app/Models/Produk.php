<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class, 'id_produk', 'id_produk')->with('rawMaterial');
    }

    public function hasRecipe()
    {
        return $this->recipes()->exists();
    }

    public function deal()
    {
        return $this->hasOne(Deal::class, 'id_produk', 'id_produk')->with('items.produk');
    }
}
