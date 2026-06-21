<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Nomor invoice unik (Contoh: INV-20260407-ABCDE)
            $table->string('invoice_number')->unique(); 
            
            // Rincian Biaya
            $table->integer('total_price_items'); // Harga produk saja
            $table->integer('shipping_cost');    // Biaya pengiriman
            $table->integer('grand_total');      // Total yang dibayar (item + ongkir)
            
            // Alamat Lengkap dari Leaflet Maps
            $table->text('address_detail'); 
            
            // Status & Bukti Bayar
            $table->string('status')->default('pending'); 
            $table->string('payment_proof')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};