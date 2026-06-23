<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Relasi ke SOP
    public function sops()
    {
        return $this->hasMany(SopContent::class, 'category_id'); // sesuaikan foreign_key jika berbeda
    }

    // Relasi ke FAQ
    public function faqs()
    {
        return $this->hasMany(FaqContent::class, 'category_id'); // sesuaikan foreign_key jika berbeda
    }
}
