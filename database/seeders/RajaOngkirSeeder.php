<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class RajaOngkirSeeder extends Seeder
{
    public function run()
    {
        $apiKey = env('RAJAONGKIR_API_KEY');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        City::truncate();
        Province::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Mengambil data Provinsi...');

        // Ambil provinsi
        $resProv = Http::withHeaders([
            'Key' => $apiKey
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        $dataProv = $resProv->json();

        if (!isset($dataProv['data'])) {
            $this->command->error('API gagal mengambil provinsi');
            dd($dataProv);
        }

        foreach ($dataProv['data'] as $p) {

            Province::create([
                'id' => $p['id'],
                'name' => $p['name']
            ]);

            $this->command->info('Mengambil kota dari provinsi: '.$p['name']);

            // Ambil kota berdasarkan province id
            $resCity = Http::withHeaders([
                'Key' => $apiKey
            ])->get("https://rajaongkir.komerce.id/api/v1/destination/city/".$p['id']);

            $dataCity = $resCity->json();

            if(isset($dataCity['data'])){

                foreach ($dataCity['data'] as $c){

                    City::create([
                        'id' => $c['id'],
                        'province_id' => $p['id'],
                        'name' => $c['name']
                    ]);

                }

            }

        }

        $this->command->info('SELESAI!');
        $this->command->info('Total Provinsi: '.Province::count());
        $this->command->info('Total Kota: '.City::count());
    }
}