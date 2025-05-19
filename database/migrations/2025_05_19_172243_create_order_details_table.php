// database/migrations/xxxx_xx_xx_xxxxxx_create_order_details_table.php
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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id(); // order_detail_id
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('restrict'); // atau onDelete('cascade') jika lebih sesuai
            $table->decimal('quantity', 8, 2); // Kuantitas, bisa desimal untuk kg
            $table->decimal('harga_saat_pesan', 10, 2); // Harga layanan saat item ini dipesan
            $table->decimal('sub_total', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};