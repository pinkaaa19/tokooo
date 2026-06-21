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
    Schema::create('knowledge_contents', function (Blueprint $table) {
        $table->id();
        $table->string('slug')->unique(); // Ini yang akan dihubungkan ke kolom 'knowledge' di tabel product
        $table->string('title');
        $table->text('description');
        $table->enum('type', ['image', 'video', 'animation']);
        $table->string('file_path'); // Path ke video/gambar/animasi lucu
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_contents');
    }
};
