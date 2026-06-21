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
    Schema::create('sop_contents', function (Blueprint $table) {
        $table->id();
        $table->string('slug')->unique();
        $table->string('title');
        $table->text('description'); // Menampung Tujuan, Langkah, dan Catatan SOP
        $table->string('file_path')->nullable(); // Lampiran gambar flowchart
        $table->string('video_url')->nullable(); // Link video YouTube
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sop_contents');
    }
};
