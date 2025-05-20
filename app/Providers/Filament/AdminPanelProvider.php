<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\LaporanPesanan;
use Filament\Navigation\MenuItem; // Pastikan MenuItem di-import

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login() // Mengaktifkan halaman login default Filament
            // ->logoutUrl(fn(): string => route('logout')) // Ini tidak lagi diperlukan jika kita override MenuItem atau menggunakan LogoutResponse binding
            ->colors([
                'primary' => Color::Amber,
            ])
            ->authGuard('web')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                LaporanPesanan::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems([
                // Anda bisa menambahkan item menu lain di sini jika perlu, contoh:
                // MenuItem::make()
                //     ->label('Profil Saya (Breeze)')
                //     ->url(fn (): string => route('profile.edit'))
                //     ->icon('heroicon-o-user-circle'),
                MenuItem::make()
                    ->label('Profil Saya') // Contoh item lain
                    ->url(fn(): string => route('profile.edit')) // Mengarah ke profile Breeze
                    ->icon('heroicon-o-user-circle'),

                // Mengganti item logout default untuk mengubah labelnya
                'logout' => MenuItem::make()
                    ->label('Sign Out') // Ganti label di sini
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                // Biarkan Filament menangani action logout-nya.
                // Action default Filament akan melakukan logout dan kemudian
                // response-nya akan di-handle oleh FilamentLogoutResponse yang sudah kita buat,
                // yang akan mengarahkannya ke route('login') Breeze.
            ]);
    }
}