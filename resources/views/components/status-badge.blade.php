@props([
    'status',
    'type' => 'order', // 'order' atau 'payment'
    'colors' => [] // Untuk fleksibilitas jika ingin override warna default
])
@php
    $defaultOrderColors = [
        'baru' => 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100',
        'diproses' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100',
        'siap diambil' => 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100',
        'selesai' => 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100',
        'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100',
    ];

    $defaultPaymentColors = [
        'lunas' => 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100',
        'belum_bayar' => 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100',
    ];

    $currentColors = [];
    if ($type === 'order') {
        $currentColors = $colors['order'] ?? $defaultOrderColors;
        $colorClasses = $currentColors[$status] ?? $defaultOrderColors['baru'];
    } elseif ($type === 'payment') {
        $currentColors = $colors['payment'] ?? $defaultPaymentColors;
        $colorClasses = $currentColors[$status] ?? $defaultPaymentColors['belum_bayar'];
    } else {
        // Fallback atau default jika tipe tidak dikenal
        $colorClasses = 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100';
    }
@endphp

<span {{ $attributes->merge(['class' => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $colorClasses]) }}>
    {{ Str::ucfirst(str_replace('_', ' ', $status)) }}
</span>