<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'service_id',
        'quantity',
        'harga_saat_pesan',
        'sub_total',
    ];

    // Relasi: Satu detail pesanan milik satu pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: Satu detail pesanan merujuk ke satu layanan
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}