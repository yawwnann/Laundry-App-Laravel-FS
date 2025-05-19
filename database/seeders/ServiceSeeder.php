<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat jika seeder dijalankan berkali-kali
        // Service::truncate(); // Hati-hati jika ada foreign key constraint

        $servicesData = [
            // Paket Kiloan
            ['tipe_paket' => 'reguler', 'satuan' => 'kg'],
            ['tipe_paket' => 'kilat', 'satuan' => 'kg'],

            // Paket Satuan (Pcs) - Contoh
            // Anda bisa menambahkan nama layanan spesifik jika logika otomatis tidak cukup
            // atau biarkan model yang men-generate nama_layanan.
            ['tipe_paket' => 'reguler', 'satuan' => 'pcs', /* 'nama_layanan' => 'Kemeja Reguler (Pcs)' */],
            ['tipe_paket' => 'kilat', 'satuan' => 'pcs', /* 'nama_layanan' => 'Kemeja Kilat (Pcs)' */],

            // Contoh layanan lain jika ada
            // ['tipe_paket' => 'reguler', 'satuan' => 'pcs', 'nama_layanan' => 'Boneka Reguler (Pcs)'],
            // ['tipe_paket' => 'express', 'satuan' => 'kg', 'nama_layanan' => 'Karpet Express (Kg)'] // Jika ada tipe paket lain
        ];

        foreach ($servicesData as $data) {
            // Biarkan event 'saving' di model Service yang mengisi 'nama_layanan', 'harga', dan 'estimasi_durasi'
            Service::create([
                'tipe_paket' => $data['tipe_paket'],
                'satuan' => $data['satuan'],
                // Jika Anda ingin override nama_layanan yang di-generate model, tambahkan di sini:
                // 'nama_layanan' => $data['nama_layanan'] ?? null, 
                // Jika Anda ingin override harga atau estimasi, juga bisa ditambahkan,
                // tapi idealnya biarkan model yang mengatur berdasarkan tipe_paket dan satuan.
            ]);
        }
    }
}