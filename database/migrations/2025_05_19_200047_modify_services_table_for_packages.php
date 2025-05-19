<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Tambahkan kolom tipe_paket
            $table->string('tipe_paket')->after('id')->comment('Contoh: reguler, kilat');
            // Buat nama_layanan nullable atau beri default jika ingin digenerate otomatis
            $table->string('nama_layanan')->nullable()->change();
            // Kita tetap simpan harga, tapi akan diisi otomatis nanti
            // Jika estimasi durasi juga tergantung paket, bisa disesuaikan di sini
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('tipe_paket');
            $table->string('nama_layanan')->nullable(false)->change(); // Kembalikan seperti semula jika perlu
        });
    }
};