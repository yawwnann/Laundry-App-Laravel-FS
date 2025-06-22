<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Customer\HelpController;
use App\Http\Controllers\Customer\ServiceController as CustomerServiceController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pesanan/baru', [CustomerOrderController::class, 'create'])->name('customer.orders.create');
    Route::post('/pesanan', [CustomerOrderController::class, 'store'])->name('customer.orders.store');
    Route::get('/pesanan/riwayat', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/pesanan/{order}', [CustomerOrderController::class, 'show'])->name('customer.orders.show'); // Halaman detail
    Route::post('/pesanan/{order}/batal', [CustomerOrderController::class, 'cancel'])->name('customer.orders.cancel'); // Aksi batal
    Route::get('/bantuan-faq', [HelpController::class, 'index'])->name('customer.help.index');
    Route::get('/layanan-kami', [CustomerServiceController::class, 'index'])->name('customer.services.index');

    // // Route logout dipindahkan ke sini dan DILINDUNGI OLEH MIDDLEWARE AUTH
    // Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    //     ->name('logout');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

require __DIR__ . '/auth.php';