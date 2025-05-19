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
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // service_id
            $table->string('nama_layanan');
            $table->decimal('harga', 10, 2); // 10 digit total, 2 digit di belakang koma
            $table->string('satuan'); // misal: 'kg', 'pcs', 'm2'
            $table->string('estimasi_durasi')->nullable(); // misal: '1 Hari', '3 Jam', dll.
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};