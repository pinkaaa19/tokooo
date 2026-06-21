<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeContent extends Model
{
    use HasFactory;

    protected $table = 'knowledge_contents'; // Sesuaikan dengan nama tabel migration Anda

    protected $fillable = [
        'title',
        'slug',
        'type',          // 'filosofi', 'sop', atau 'faq'
        'description',   // Narasi artikel filosofi / deskripsi SOP
        'group_name',    // Kelompok motif Toraja
        'source',        // Sumber informasi (teks/link)
        'video_url',     // URL youtube jika ada
        'file_path',     // Path file gambar di storage
        'question',      // Khusus FAQ
        'answer',        // Khusus FAQ
        'target_actor',  // Khusus SOP (Internal/Pelanggan)
        'category_id'    // Relasi pengunci ke kategori e-commerce produk tekstil
    ];

    /**
     * Relasi Many-to-Many untuk fitur "Motif Terkait"
     */
    public function relatedMotifs()
    {
        return $this->belongsToMany(KnowledgeContent::class, 'motif_relations', 'knowledge_id', 'related_id');
    }

    /**
     * Relasi BelongsTo ke Kategori Produk Tekstil Aldi Art
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}