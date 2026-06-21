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
    Schema::create('faq_contents', function (Blueprint $table) {
        $table->id();
        $table->string('question'); // Untuk menyimpan data Pertanyaan pembeli
        $table->text('answer');      // Untuk menyimpan data Jawaban/Solusi produk
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_contents');
    }
};
