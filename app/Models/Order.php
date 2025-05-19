<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_pesanan',
        'order_date',
        'status_pesanan',
        'total_amount',
        'catatan_pelanggan',
        'payment_status',
        'paid_at',
        'processed_by_admin_id',
        'catatan_internal',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Relasi: Satu pesanan dimiliki oleh satu user (pelanggan)
    public function customer() // Menggunakan 'customer' agar lebih deskriptif
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Satu pesanan diproses oleh satu user (admin)
    public function processedByAdmin()
    {
        return $this->belongsTo(User::class, 'processed_by_admin_id');
    }

    // Relasi: Satu pesanan memiliki banyak detail pesanan
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}