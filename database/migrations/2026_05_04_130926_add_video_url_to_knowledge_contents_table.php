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
    Schema::table('knowledge_contents', function (Blueprint $table) {
        // Menambahkan kolom video_url setelah kolom file_path
        // nullable() penting agar data lama tidak error karena kolom ini kosong
        $table->string('video_url')->nullable()->after('file_path');
    });
}

public function down()
{
    Schema::table('knowledge_contents', function (Blueprint $table) {
        $table->dropColumn('video_url');
    });
}
};
