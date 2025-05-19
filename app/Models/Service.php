<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe_paket',
        'nama_layanan',
        'harga',
        'satuan',
        'estimasi_durasi',
    ];

    // Definisikan harga per paket per kg
    public const HARGA_PAKET_KG = [
        'reguler' => 5000,
        'kilat' => 8000,
    ];

    // Definisikan harga per paket per pcs (CONTOH, sesuaikan jika perlu)
    // Jika harga pcs tidak tergantung paket, Anda bisa buat struktur lain
    public const HARGA_PAKET_PCS = [
        'reguler' => 1500, // Contoh harga pcs reguler
        'kilat' => 2500,   // Contoh harga pcs kilat
    ];

    public const ESTIMASI_DURASI_PAKET = [
        'reguler' => '2-3 Hari',
        'kilat' => '1 Hari',
    ];

    protected static function booted(): void
    {
        static::saving(function ($service) {
            if (!$service->tipe_paket || !$service->satuan) {
                // Jika tipe paket atau satuan belum diisi, jangan proses lebih lanjut
                // untuk menghindari error, biarkan validasi Filament yang menangani
                return;
            }

            $namaLayananBase = "Paket " . ucfirst($service->tipe_paket);
            $estimasi = self::ESTIMASI_DURASI_PAKET[$service->tipe_paket] ?? 'N/A';
            $harga = 0;

            if ($service->satuan === 'kg') {
                $harga = self::HARGA_PAKET_KG[$service->tipe_paket] ?? 0;
                $namaLayananBase .= " (Kg)";
            } elseif ($service->satuan === 'pcs') {
                $harga = self::HARGA_PAKET_PCS[$service->tipe_paket] ?? 0; // Menggunakan HARGA_PAKET_PCS
                $namaLayananBase .= " (Pcs)";
            }
            // Anda bisa menambahkan logika lain jika ada satuan lain

            $service->nama_layanan = $service->nama_layanan ?: $namaLayananBase; // Isi jika nama layanan kosong
            $service->harga = $harga;
            $service->estimasi_durasi = $estimasi;
        });
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}