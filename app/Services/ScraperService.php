<?php

namespace App\Services;

use Exception;

class ScraperService
{
    /**
     * Scraping harga dari Berdu dengan tipe data yang jelas.
     * Menghilangkan peringatan "no type information" pada image_55891c.png.
     */
   public function scrapeBerduSingle(string $destinationName): int
{
    sleep(5); // Tingkatkan jeda menjadi 5 detik untuk menghindari blokir

    $url = "https://berdu.id/api/shipping/cost";
    $ch = curl_init();
    
    $postData = json_encode([
        'origin' => 'Toraja Utara',
        'destination' => $destinationName,
        'weight' => 1000,
        'couriers' => ['jnt'] 
    ]);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Requested-With: XMLHttpRequest'
    ]);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/121.0.0.0');

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // DEBUG: Jika gagal, kita ingin tahu kenapa
    if ($httpCode !== 200) {
        throw new Exception("HTTP Error $httpCode: Server Berdu menolak akses. Coba ganti koneksi internet (Tethering).");
    }

    $data = json_decode($response, true);

    if (isset($data['results'][0]['costs'][0]['cost'])) {
        return (int) $data['results'][0]['costs'][0]['cost'];
    }

    // Jika response kosong, tampilkan isi response asli untuk pengecekan
    throw new Exception("Data tidak ditemukan. Response: " . substr($response, 0, 100));
}
}