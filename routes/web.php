<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; // Asumsi ini dari Breeze
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pesanan/baru', [CustomerOrderController::class, 'create'])->name('customer.orders.create');
    Route::post('/pesanan', [CustomerOrderController::class, 'store'])->name('customer.orders.store');

});

require __DIR__ . '/auth.php';