<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Service; // Tambahkan ini untuk menggunakan model Service

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $services = Service::orderBy('nama_layanan', 'asc')->get(); // Ambil semua layanan, urutkan berdasarkan nama

        // Anda bisa menambahkan logika lain di sini jika perlu,
        // misalnya mengambil beberapa pesanan terbaru pelanggan untuk ditampilkan juga.

        return view('customer.dashboard', compact('user', 'services')); // Kirim 'services' ke view
    }
}