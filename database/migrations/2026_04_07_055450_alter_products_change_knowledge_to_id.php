<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bersihkan data string kosong agar tidak error saat ganti tipe data
        DB::table('products')->where('knowledge', '')->update(['knowledge' => null]);

        Schema::table('products', function (Blueprint $table) {
            // 2. Ubah nama kolom dan tipe datanya
            // Kita gunakan unsignedBigInteger agar cocok dengan ID di tabel knowledge
            $table->unsignedBigInteger('knowledge')->nullable()->change();
            
            // 3. Rename kolom agar lebih standar (Opsional tapi disarankan)
            $table->renameColumn('knowledge', 'knowledge_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('knowledge_id', 'knowledge');
            $table->text('knowledge')->nullable()->change();
        });
    }
};