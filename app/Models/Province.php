<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    protected $primaryKey = 'id';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = ['id', 'name'];

    public function cities()
    {
        return $this->hasMany(City::class, 'province_id', 'id');
    }
}