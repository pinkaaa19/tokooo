<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::table('products', function (Blueprint $table) {
        // Menyimpan "Hitam,Maroon,Putih"
        $table->string('available_colors')->nullable()->after('price'); 
        // Menyimpan "S,M,L,XL"
        $table->string('available_sizes')->nullable()->after('available_colors'); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
