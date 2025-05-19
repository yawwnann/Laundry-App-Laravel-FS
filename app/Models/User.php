<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser; // Tambahkan ini
use Filament\Panel; // Tambahkan ini

class User extends Authenticatable implements FilamentUser // Implementasikan FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahkan role
        'phone', // Tambahkan phone
        'address', // Tambahkan address
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Implementasi untuk FilamentUser
    public function canAccessPanel(Panel $panel): bool
    {
        // Izinkan akses ke panel jika role adalah 'admin'
        return $this->role === 'admin';
        // Anda bisa menyesuaikan nama panel jika tidak default 'admin'
        // return $this->role === 'admin' && $panel->getId() === 'admin';
    }

    // Relasi: User bisa memiliki banyak orders (sebagai pelanggan)
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    // Relasi: User (admin) bisa memproses banyak orders
    public function processedOrders()
    {
        return $this->hasMany(Order::class, 'processed_by_admin_id');
    }
}