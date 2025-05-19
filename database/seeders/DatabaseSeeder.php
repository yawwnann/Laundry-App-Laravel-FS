<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder Anda di sini dalam urutan yang benar
        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
            OrderSeeder::class, // OrderSeeder dijalankan terakhir karena butuh User dan Service
        ]);
    }
}