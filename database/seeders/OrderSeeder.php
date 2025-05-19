<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Str;
use Carbon\Carbon; // Untuk manipulasi tanggal

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID pelanggan dan admin yang ada
        $customerIds = User::where('role', 'pelanggan')->pluck('id')->toArray();
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        $serviceIds = Service::pluck('id')->toArray();

        if (empty($customerIds) || empty($serviceIds)) {
            $this->command->info('Tidak ada pelanggan atau layanan yang tersedia untuk membuat pesanan. Silakan jalankan UserSeeder dan ServiceSeeder terlebih dahulu.');
            return;
        }

        $faker = \Faker\Factory::create('id_ID'); // Menggunakan Faker Indonesia

        for ($i = 0; $i < 50; $i++) { // Buat 50 pesanan sampel
            $customer = User::find($faker->randomElement($customerIds));
            $orderDate = Carbon::instance($faker->dateTimeBetween('-3 months', 'now')); // Pesanan dalam 3 bulan terakhir

            $orderStatusOptions = ['baru', 'diproses', 'siap diambil', 'selesai', 'dibatalkan'];
            $paymentStatusOptions = ['belum_bayar', 'lunas'];

            $statusPesanan = $faker->randomElement($orderStatusOptions);
            $paymentStatus = ($statusPesanan === 'dibatalkan') ? 'belum_bayar' : $faker->randomElement($paymentStatusOptions);

            $paidAt = null;
            $processedByAdminId = null;

            if ($paymentStatus === 'lunas' && $statusPesanan !== 'dibatalkan') {
                $paidAt = $orderDate->copy()->addDays($faker->numberBetween(0, 5))->addHours($faker->numberBetween(1, 12));
                if (!empty($adminIds)) {
                    $processedByAdminId = $faker->randomElement($adminIds);
                }
            }

            if ($statusPesanan === 'selesai' && $paymentStatus === 'belum_bayar') {
                // Jika selesai tapi belum bayar, pastikan tidak ada paid_at
                $paidAt = null;
            }


            $order = Order::create([
                'user_id' => $customer->id,
                'kode_pesanan' => 'INV-' . strtoupper(Str::random(4)) . '-' . $orderDate->format('YmdHis') . $i,
                'order_date' => $orderDate,
                'status_pesanan' => $statusPesanan,
                'total_amount' => 0, // Akan diupdate nanti
                'catatan_pelanggan' => $faker->optional(0.3)->sentence, // 30% kemungkinan ada catatan
                'payment_status' => $paymentStatus,
                'paid_at' => $paidAt,
                'processed_by_admin_id' => $processedByAdminId,
                'catatan_internal' => $faker->optional(0.2)->sentence,
                'created_at' => $orderDate,
                'updated_at' => $orderDate->copy()->addHours($faker->numberBetween(1, 24)),
            ]);

            $numberOfItems = $faker->numberBetween(1, 4); // 1 sampai 4 item per pesanan
            $currentTotalAmount = 0;

            for ($j = 0; $j < $numberOfItems; $j++) {
                $service = Service::find($faker->randomElement($serviceIds));
                if (!$service)
                    continue;

                $quantity = ($service->satuan === 'kg') ? $faker->randomFloat(1, 0.5, 5) : $faker->numberBetween(1, 10); // Kuantitas kg atau pcs
                $hargaSaatPesan = $service->harga; // Ambil harga dari master layanan
                $subTotal = $hargaSaatPesan * $quantity;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'service_id' => $service->id,
                    'quantity' => $quantity,
                    'harga_saat_pesan' => $hargaSaatPesan,
                    'sub_total' => $subTotal,
                ]);
                $currentTotalAmount += $subTotal;
            }

            // Update total_amount di pesanan
            $order->total_amount = $currentTotalAmount;
            $order->save();
        }
    }
}