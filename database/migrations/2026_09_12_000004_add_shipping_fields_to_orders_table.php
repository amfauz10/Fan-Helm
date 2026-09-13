<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // subtotal_amount = total harga produk saja (sebelum ongkir).
            // total_amount (kolom lama) tetap dipakai sebagai GRAND TOTAL
            // yang dikirim ke Midtrans, supaya tidak mengubah kontrak yang
            // sudah dipakai di banyak tempat (invoice, email, dsb).
            $table->decimal('subtotal_amount', 12, 2)->nullable()->after('total_amount');
            $table->string('shipping_city')->nullable()->after('customer_address');
            $table->unsignedInteger('shipping_cost')->default(0)->after('shipping_city');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal_amount', 'shipping_city', 'shipping_cost']);
        });
    }
};
