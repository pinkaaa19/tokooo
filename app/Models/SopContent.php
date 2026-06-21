<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SopContent extends Model
{
    use HasFactory;

    protected $table = 'sop_contents';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'category',
        'file_path',
        'video_url'
    ];
}
