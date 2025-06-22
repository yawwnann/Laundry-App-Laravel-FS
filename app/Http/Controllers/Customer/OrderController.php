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
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\User;

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
            'delivery_option' => 'required|string|in:dijemput,diantar',
            'phone' => 'required|string|max:20',
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

            // Update nomor telepon pengguna jika berubah
            $user = User::find(Auth::id());
            if ($user && $user->phone !== $validatedData['phone']) {
                $user->phone = $validatedData['phone'];
                $user->save();
            }

            // Buat Order Utama
            $order = Order::create([
                'user_id' => $user->id,
                'kode_pesanan' => 'INV-' . strtoupper(Str::random(4)) . '-' . date('YmdHis'),
                'order_date' => now(),
                'status_pesanan' => 'baru', // Status awal pesanan
                'payment_status' => 'belum_bayar', // Status awal pembayaran
                'delivery_option' => $validatedData['delivery_option'],
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
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with('orderDetails.service') // Eager load detail dan layanan di detail
            ->orderBy('created_at', 'desc') // Tampilkan yang terbaru dulu
            ->paginate(10); // Gunakan paginasi jika pesanannya banyak

        return view('customer.orders.index', compact('orders'));
    }
    public function show(Order $order) // Menggunakan Route Model Binding
    {
        // Pastikan pesanan ini milik pengguna yang sedang login
        if (Auth::id() !== $order->user_id) {
            // abort(403, 'Anda tidak memiliki izin untuk melihat pesanan ini.');
            // Atau redirect dengan pesan error
            return redirect()->route('customer.orders.index')->with('error', 'Anda tidak memiliki izin untuk melihat pesanan tersebut.');
        }

        // Eager load detail dan layanan di detail, juga customer jika perlu (meskipun sudah ada di $order->user_id)
        $order->load('orderDetails.service', 'customer');

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Membatalkan pesanan.
     */
    public function cancel(Request $request, Order $order)
    {
        // Pastikan pesanan ini milik pengguna yang sedang login
        if (Auth::id() !== $order->user_id) {
            return redirect()->route('customer.orders.index')->with('error', 'Aksi tidak diizinkan.');
        }

        // Hanya boleh dibatalkan jika statusnya masih 'baru'
        if ($order->status_pesanan === 'baru') {
            $order->status_pesanan = 'dibatalkan';
            // Anda bisa menambahkan logika lain, misalnya:
            // $order->payment_status = 'dibatalkan'; // jika relevan
            // $order->catatan_internal = ($order->catatan_internal ? $order->catatan_internal . "\n" : "") . "Dibatalkan oleh pelanggan pada " . now()->isoFormat('D MMM YYYY, HH:mm');
            $order->save();

            return redirect()->route('customer.orders.show', $order)->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->route('customer.orders.show', $order)->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
    }
}