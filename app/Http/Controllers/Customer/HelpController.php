<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View; // Tambahkan ini

class HelpController extends Controller
{
    /**
     * Menampilkan halaman Bantuan & FAQ.
     */
    public function index(): View
    {
        // Anda bisa mengambil data FAQ dari database atau menuliskannya langsung di view
        // Untuk contoh ini, kita akan biarkan view yang menanganinya atau mengirim array sederhana
        $faqs = [
            [
                'question' => 'Bagaimana cara membuat pesanan baru?',
                'answer' => 'Anda dapat membuat pesanan baru dengan mengklik tombol "Buat Pesanan Baru" di dashboard atau menu navigasi. Pilih layanan yang diinginkan, tentukan kuantitas, lalu klik "Buat Pesanan".',
            ],
            [
                'question' => 'Bagaimana cara melihat status pesanan saya?',
                'answer' => 'Status pesanan Anda dapat dilihat pada halaman "Riwayat Pesanan". Klik "Detail" untuk melihat informasi lebih lanjut.',
            ],
            [
                'question' => 'Kapan saya bisa mengambil pesanan saya?',
                'answer' => 'Anda akan diberitahu (jika fitur notifikasi ada) atau bisa memeriksa status pesanan. Jika statusnya "Siap Diambil", Anda bisa datang ke lokasi kami.',
            ],
            [
                'question' => 'Bagaimana cara membatalkan pesanan?',
                'answer' => 'Pesanan hanya dapat dibatalkan jika statusnya masih "Baru". Buka halaman detail pesanan Anda dan klik tombol "Batalkan Pesanan".',
            ],
            [
                'question' => 'Metode pembayaran apa saja yang diterima?',
                'answer' => 'Saat ini kami menerima pembayaran tunai saat Anda mengambil pesanan. Untuk metode pembayaran lain, silakan hubungi kami.',
            ],
        ];
        return view('customer.help.index', compact('faqs'));
    }
}