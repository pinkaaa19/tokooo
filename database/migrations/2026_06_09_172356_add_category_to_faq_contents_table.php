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
    Schema::table('faq_contents', function (Blueprint $table) {
        $table->string('category')->nullable(); // Menambahkan kolom kategori
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faq_contents', function (Blueprint $table) {
            //
        });
    }
};
