<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service; // Import model Service
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Menampilkan daftar layanan untuk pelanggan.
     */
    public function index(): View
    {
        $services = Service::orderBy('tipe_paket', 'asc') // Urutkan berdasarkan tipe paket dulu
            ->orderBy('nama_layanan', 'asc') // Lalu berdasarkan nama layanan
            ->get();
        return view('customer.services.index', compact('services'));
    }
}