<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    // Izinkan kolom-kolom ini diisi data
    protected $fillable = ['user_id', 'category_name','goal'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}