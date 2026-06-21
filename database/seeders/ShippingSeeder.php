<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Services\ScraperService;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        // Menghindari timeout saat proses scraping yang lama
        set_time_limit(0); 

        $scraper = new ScraperService();
        
        // Pastikan tabel cities sudah terisi data wilayah Berdu
        $cities = DB::table('cities')->get(); 

        $this->command->info("Memulai pengisian harga J&T dari TORAJA UTARA...");

        foreach ($cities as $city) {
            try {
                /** 
                 * Perbaikan dari image_5603e4.png:
                 * Gunakan $city->name (tanpa tanda $ pada properti)
                 */
                $price = $scraper->scrapeBerduSingle($city->name);

                DB::table('shipping_rates')->updateOrInsert(
                    [
                        'destination_city' => $city->name,
                        'courier' => 'J&T'
                    ],
                    [
                        'origin_city'  => 'TORAJA UTARA',
                        'price_per_kg' => $price,
                        'updated_at'   => now()
                    ]
                );

                $this->command->info("Berhasil: Toraja Utara -> {$city->name} (Rp " . number_format($price) . ")");
                
            } catch (\Exception $e) {
                $this->command->error("Gagal di {$city->name}: " . $e->getMessage());
            }
        }

        $this->command->info("Proses Seeding Selesai!");
    }
}