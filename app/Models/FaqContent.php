<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqContent extends Model
{
    use HasFactory;

    protected $table = 'faq_contents';

    // Tambahkan 'category' di sini agar data bisa tersimpan ke database
    protected $fillable = ['question', 'answer', 'category'];

    public function feedbacks() {
    return $this->hasMany(FaqFeedback::class, 'faq_content_id');
}
}