<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menyimpan stok per ukuran dalam bentuk JSON, contoh:
     * {"S": 5, "M": 10, "L": 8, "XL": 0}
     * Dipilih JSON (bukan tabel terpisah) supaya konsisten dengan pola
     * 'sizes' & 'gallery_images' yang sudah dipakai di tabel ini.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('stock')->nullable()->after('sizes');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};
