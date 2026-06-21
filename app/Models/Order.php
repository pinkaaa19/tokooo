<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Memungkinkan pengisian massal untuk semua kolom
    protected $guarded = []; 

    /**
     * Relasi ke User (PENTING: Agar error RelationNotFound hilang)
     * Satu pesanan dimiliki oleh satu pengguna
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Item Pesanan
     * Satu pesanan bisa memiliki banyak item produk
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}