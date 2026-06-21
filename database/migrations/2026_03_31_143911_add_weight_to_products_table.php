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
    Schema::table('products', function (Blueprint $table) {
        // Kita gunakan integer (satuan gram) agar mudah dihitung
        $table->integer('weight')->default(250)->after('price'); 
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('weight');
    });
}
};
