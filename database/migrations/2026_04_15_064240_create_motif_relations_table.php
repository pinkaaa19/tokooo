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
    Schema::create('motif_relations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('knowledge_id');
        $table->unsignedBigInteger('related_id');
        
        $table->foreign('knowledge_id')->references('id')->on('knowledge_contents')->onDelete('cascade');
        $table->foreign('related_id')->references('id')->on('knowledge_contents')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motif_relations');
    }
};
