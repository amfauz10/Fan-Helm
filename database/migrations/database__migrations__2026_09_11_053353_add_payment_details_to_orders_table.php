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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
            $table->string('midtrans_order_id')->nullable()->after('snap_token');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('payment_type')->nullable()->after('midtrans_transaction_id');
            $table->string('fraud_status')->nullable()->after('payment_type');
            $table->timestamp('paid_at')->nullable()->after('fraud_status');
            $table->json('payment_raw_response')->nullable()->after('paid_at');
        });

        // Perbaikan: sebelumnya order langsung dianggap "success" tanpa proses
        // pembayaran apa pun. Sekarang order baru dibuat berstatus "pending"
        // sampai Midtrans mengonfirmasi pembayaran lewat webhook resmi.
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
            $table->string('bank_name')->nullable()->default(null)->change();
            $table->string('account_number')->nullable()->default(null)->change();
            $table->string('recipient_name')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'midtrans_order_id',
                'midtrans_transaction_id',
                'payment_type',
                'fraud_status',
                'paid_at',
                'payment_raw_response',
            ]);
        });
    }
};
