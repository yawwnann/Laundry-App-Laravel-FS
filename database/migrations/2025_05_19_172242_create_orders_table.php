<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // order_id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_pesanan')->unique();
            $table->timestamp('order_date')->useCurrent();
            $table->string('status_pesanan')->default('baru');
            // status_pesanan: 'baru', 'diproses', 'siap diambil', 'selesai', 'dibatalkan'

            $table->decimal('total_amount', 12, 2)->nullable();
            $table->text('catatan_pelanggan')->nullable();

            // Kolom terkait pembayaran langsung
            $table->string('payment_status')->default('belum_bayar');
            // payment_status: 'belum_bayar', 'lunas'
            $table->timestamp('paid_at')->nullable(); // Waktu ketika pembayaran dikonfirmasi
            $table->foreignId('processed_by_admin_id')->nullable()->constrained('users')->onDelete('set null'); // Admin yang memproses pembayaran/penyerahan
            $table->text('catatan_internal')->nullable(); // Catatan dari admin terkait order/pembayaran

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};