<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $table = 'raw_materials';
    protected $guarded = [];

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class, 'id_raw_material', 'id');
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stok <= min_stok');
    }
}
