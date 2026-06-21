<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('province_id');
            $table->string('name');
            $table->timestamps();

            // Foreign Key ke tabel provinces
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
};