<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Untuk transaksi
use Illuminate\Validation\Rule; // Untuk validasi
use Illuminate\Support\Facades\Log; // Untuk logging

class OrderController extends Controller
{
    public function create()
    {
        $services = Service::orderBy('nama_layanan', 'asc')->get();
        // dd($services); // Untuk tes apakah query services berhasil
        return view('customer.orders.create', compact('services'));
    }

    public function store(Request $request)
    {
        // Validasi input utama
        $validatedData = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.service_id' => [
                'required',
                Rule::exists('services', 'id'), // Pastikan service_id valid
            ],
            'items.*.quantity' => 'required|numeric|min:0.1',
            'catatan_pelanggan' => 'nullable|string|max:1000',
        ]);

        // Memulai transaksi database agar konsisten
        DB::beginTransaction();

        try {
            $grandTotal = 0;
            $orderDetailsData = [];

            foreach ($validatedData['items'] as $item) {
                $service = Service::find($item['service_id']);
                if (!$service) {
                    // Seharusnya tidak terjadi jika validasi exists() bekerja
                    DB::rollBack();
                    return back()->withInput()->withErrors(['items' => 'Layanan tidak valid dipilih.']);
                }

                $quantity = (float) $item['quantity'];
                $hargaSatuan = $service->harga; // Ambil harga dari database, bukan dari frontend
                $subTotal = $hargaSatuan * $quantity;

                $orderDetailsData[] = [
                    'service_id' => $service->id,
                    'quantity' => $quantity,
                    'harga_saat_pesan' => $hargaSatuan,
                    'sub_total' => $subTotal,
                    // order_id akan diisi setelah order utama dibuat
                ];
                $grandTotal += $subTotal;
            }

            if (empty($orderDetailsData)) {
                DB::rollBack();
                return back()->withInput()->withErrors(['items' => 'Minimal satu layanan harus dipilih.']);
            }

            // Buat Order Utama
            $order = Order::create([
                'user_id' => Auth::id(),
                'kode_pesanan' => 'INV-' . strtoupper(Str::random(4)) . '-' . date('YmdHis'),
                'order_date' => now(),
                'status_pesanan' => 'baru', // Status awal pesanan
                'payment_status' => 'belum_bayar', // Status awal pembayaran
                'total_amount' => $grandTotal,
                'catatan_pelanggan' => $validatedData['catatan_pelanggan'] ?? null,
            ]);

            // Simpan Order Details
            foreach ($orderDetailsData as $detail) {
                $order->orderDetails()->create($detail);
            }

            DB::commit(); // Jika semua berhasil, commit transaksi

            // Redirect ke halaman sukses atau halaman detail pesanan
            // Untuk sekarang, kita redirect ke dashboard pelanggan dengan pesan sukses
            return redirect()->route('customer.dashboard')->with('success', 'Pesanan Anda berhasil dibuat dengan kode: ' . $order->kode_pesanan);

        } catch (\Exception $e) {
            DB::rollBack(); // Jika ada error, rollback transaksi
            // Log errornya
            log::error('Error saat membuat pesanan: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.');
        }
    }
}