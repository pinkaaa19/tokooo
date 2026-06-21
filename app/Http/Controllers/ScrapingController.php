<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use voku\helper\HtmlDomParser;
use App\Models\ShippingRate;

class ScrapingController extends Controller
{
    public function scrape()
    {
        // contoh dataset hasil scraping

        $cities = [
            'Makassar' => 15000,
            'Manado' => 25000,
            'Jakarta' => 45000,
            'Surabaya' => 40000
        ];

        foreach($cities as $city => $price){

            ShippingRate::create([
                'origin_city' => 'Toraja',
                'destination_city' => $city,
                'price_per_kg' => $price
            ]);

        }

        return "Dataset ongkir berhasil disimpan";
    }
}