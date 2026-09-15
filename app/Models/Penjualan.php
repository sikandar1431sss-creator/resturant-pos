<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'kitchen_started_at',
        'kitchen_ready_at',
        'kitchen_served_at',
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function detail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id_penjualan');
    }

    public function scopeActiveKitchen($query)
    {
        return $query->whereIn('kitchen_status', ['pending', 'cooking', 'ready'])
                     ->where('total_item', '>', 0);
    }
}

