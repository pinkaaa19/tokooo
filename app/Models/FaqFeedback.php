<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaqFeedback extends Model
{
    // Tambahkan ini agar Laravel tahu tabel mana yang digunakan (opsional tapi disarankan)
    protected $table = 'faq_feedbacks';

    // Sudah benar: Membatasi kolom yang boleh diisi melalui mass assignment
    protected $fillable = ['faq_content_id', 'is_helpful', 'ip_address'];

    /**
     * Relasi ke FAQ Content
     * Tambahkan return type hint untuk standar koding yang lebih baik
     */
    public function faq(): BelongsTo 
    {
        return $this->belongsTo(FaqContent::class, 'faq_content_id');
    }
}