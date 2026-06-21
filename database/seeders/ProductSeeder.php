<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // Pastikan ini ada

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        Product::create([
            'name' => 'Jersey laki-laki Motif',
            'category' => 'Jersey',
            'price' => 60000,
            'description' => 'Tenun tangan asli dengan motif khas Toraja.',
            'image' => 'product\Jersey laki-laki.jpeg'
        ]);

        // Kamu bisa tambah Product::create lagi sebanyak yang kamu mau
    }
}