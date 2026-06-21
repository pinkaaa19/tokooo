<?php

namespace App\Http\Controllers\Admin; 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'category',         
        'weight',           
        'available_colors',
        'available_sizes',
        'target_gender',
        'knowledge_id',     
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function knowledge()
    {
        return $this->belongsTo(KnowledgeContent::class, 'knowledge_id');
    }
    public function knowledgeData()
    {

    return $this->belongsTo(KnowledgeContent::class, 'knowledge_id');
    }
    public function knowledgeContent()
    {
        return $this->belongsTo(KnowledgeContent::class, 'knowledge_id');
    }

}

