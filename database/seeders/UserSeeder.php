<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Admin Utama
        User::create([
            'name' => 'Admin Laundry',
            'email' => 'admin@laundry.test',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1',
            'email_verified_at' => now(),
        ]);

        // Buat Admin Tambahan (Opsional)
        User::create([
            'name' => 'Staff Admin',
            'email' => 'staff@laundry.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '089876543210',
            'address' => 'Jl. Staff No. 2',
            'email_verified_at' => now(),
        ]);

        // Buat Beberapa Pelanggan menggunakan Factory (lebih baik) atau create manual
        // Jika menggunakan factory, pastikan UserFactory Anda sudah disesuaikan
        // User::factory(20)->create(); // Ini jika UserFactory sudah diatur untuk role pelanggan

        // Atau buat pelanggan secara manual:
        User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@pelanggan.test',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'phone' => '081111111111',
            'address' => 'Jl. Pelanggan Budi No. 10',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Ani Pelanggan',
            'email' => 'ani@pelanggan.test',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'phone' => '082222222222',
            'address' => 'Jl. Pelanggan Ani No. 20',
            'email_verified_at' => now(),
        ]);

        // Untuk data yang lebih banyak, gunakan factory.
        // Pastikan UserFactory Anda menghasilkan user dengan role 'pelanggan'
        // dan mengisi field phone & address jika perlu.
        // Contoh modifikasi UserFactory:
        // File: database/factories/UserFactory.php
        /*
        public function definition(): array
        {
            return [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => static::$password ??= Hash::make('password'),
                'remember_token' => Str::random(10),
                'role' => 'pelanggan', // Default role
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
            ];
        }
        // Tambahkan ini di UserFactory.php untuk membuat admin jika perlu:
        public function admin(): static
        {
            return $this->state(fn (array $attributes) => [
                'role' => 'admin',
            ]);
        }
        */
        // Jika UserFactory sudah siap, Anda bisa buat banyak pelanggan seperti ini:
        if (class_exists(\Database\Factories\UserFactory::class)) {
            User::factory(10)->create(); // Membuat 10 pelanggan tambahan
        }
    }
}