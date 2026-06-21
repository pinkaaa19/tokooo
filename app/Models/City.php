<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    public $incrementing = false; // Karena ID manual dari RajaOngkir
    protected $keyType = 'string';

    protected $fillable = ['id', 'province_id', 'name'];
}