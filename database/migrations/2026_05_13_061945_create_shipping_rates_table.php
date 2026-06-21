<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('shipping_rates', function (Blueprint $table) {
        $table->id();
        $table->string('origin_city')->default('RANTEPAO'); 
        $table->string('destination_city')->index(); // Ditambah index untuk performa query
        $table->integer('price_per_kg');             // Menggunakan nama kolom Anda
        $table->string('courier')->default('JNE');   // Identitas kurir
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
