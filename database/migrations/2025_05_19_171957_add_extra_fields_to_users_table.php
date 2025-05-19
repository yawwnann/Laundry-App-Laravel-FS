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
        Schema::table('users', function (Blueprint $table) {
            // Pastikan kolom ditambahkan setelah kolom tertentu jika diinginkan,
            // contoh: $table->string('role')->after('email_verified_at')->default('pelanggan');
            $table->string('role')->default('pelanggan'); // Contoh: 'admin', 'pelanggan'
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address']);
        });
    }
};