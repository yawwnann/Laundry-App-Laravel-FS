<?php

namespace App\Http\Responses; // Pastikan namespace benar

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;

class FilamentLogoutResponse implements LogoutResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        // Arahkan ke halaman login Breeze setelah logout dari Filament
        return redirect()->route('login');
    }
}