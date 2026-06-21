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
    Schema::table('sop_contents', function (Blueprint $table) {
        // Menambahkan kolom category setelah kolom description
        $table->string('category')->after('description');
    });
}

    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('sop_contents', function (Blueprint $table) {
        // Jika migration dibatalkan (rollback), hapus kolom category
        $table->dropColumn('category');
    });
}
};
