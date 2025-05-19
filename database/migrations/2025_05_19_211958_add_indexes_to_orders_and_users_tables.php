<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('payment_status');
            $table->index('status_pesanan');
            // user_id sudah seharusnya terindeks jika menggunakan foreignId()->constrained()
            // tapi bisa ditambahkan secara eksplisit jika ragu: $table->index('user_id');
            // processed_by_admin_id juga bisa dipertimbangkan jika sering difilter
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role'); // Untuk filter pelanggan/admin
        });

        Schema::table('order_details', function (Blueprint $table) {
            // order_id dan service_id sudah seharusnya terindeks karena foreign key
            // $table->index('order_id');
            // $table->index('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['created_at']); // Nama indeks default adalah nama_tabel_nama_kolom_index
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['status_pesanan']);
            // $table->dropIndex(['user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });

        // Tidak perlu dropIndex untuk order_details jika hanya foreign key default
    }
};