<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
   'origin_city',
   'destination_city',
   'price_per_kg'
];
}
