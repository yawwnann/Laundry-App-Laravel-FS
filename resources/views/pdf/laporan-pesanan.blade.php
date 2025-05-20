<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pesanan</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 0; padding: 0; font-size: 10px; }
        .container { padding: 20px; }
        h1 { text-align: center; margin-bottom: 20px; font-size: 16px; }
        h2 { font-size: 14px; margin-top: 30px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px;}
        .header-info { margin-bottom: 20px; font-size: 11px; }
        .header-info p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            text-transform: capitalize;
            color: #fff;
        }
        .bg-gray { background-color: #e5e7eb; color: #374151; }
        .bg-yellow { background-color: #fef3c7; color: #92400e; }
        .bg-blue { background-color: #dbeafe; color: #1e40af; }
        .bg-green { background-color: #d1fae5; color: #065f46; }
        .bg-red { background-color: #fee2e2; color: #991b1b; }
        .stats-container { display: flex; justify-content: space-around; margin-bottom: 20px; } /* Tidak bisa flex di DomPDF dasar */
        .stat-card { border: 1px solid #eee; padding: 10px; width: 30%; text-align: center; margin: 0 5px;} /* Styling manual */
        .stat-card p { margin: 5px 0; }
        .stat-label { font-size: 10px; color: #666; }
        .stat-value { font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Pesanan</h1>
        <div class="header-info">
            <p><strong>Periode Laporan:</strong> {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM YYYY') }}</p>
            <p><strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY, HH:mm') }}</p>
        </div>

        <h2>Ringkasan Statistik</h2>
        {{-- DomPDF memiliki dukungan terbatas untuk CSS modern seperti flexbox. Kita buat manual --}}
         <table style="width:100%; margin-bottom: 20px;">
             <tr>
                 <td style="width:33%; border: 1px solid #eee; text-align:center; padding:10px;">
                     <p class="stat-label">Total Pesanan</p>
                     <p class="stat-value">{{ $totalOrders }}</p>
                 </td>
                 <td style="width:33%; border: 1px solid #eee; text-align:center; padding:10px;">
                     <p class="stat-label">Total Pendapatan (Lunas)</p>
                     <p class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                 </td>
                 <td style="width:33%; border: 1px solid #eee; text-align:center; padding:10px;">
                     <p class="stat-label">Pelanggan Baru</p>
                     <p class="stat-value">{{ $newCustomers }}</p>
                 </td>
             </tr>
         </table>


        <h2>Detail Pesanan</h2>
        @if(!empty($ordersData))
            <table>
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tgl Pesan</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordersData as $order)
                        <tr>
                            <td>{{ $order['kode_pesanan'] }}</td>
                            <td>{{ $order['customer']['name'] ?? 'N/A' }}</td>
                            <td>{{ $order['order_date'] ? \Carbon\Carbon::parse($order['order_date'])->isoFormat('D MMM YY, HH:mm') : 'N/A' }}</td>
                            <td class="text-right">Rp {{ number_format($order['total_amount'], 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge 
                                    @switch($order['status_pesanan'])
                                        @case('baru') bg-gray @break
                                        @case('diproses') bg-yellow @break
                                        @case('siap diambil') bg-blue @break
                                        @case('selesai') bg-green @break
                                        @case('dibatalkan') bg-red @break
                                        @default bg-gray
                                    @endswitch
                                ">
                                    {{ Str::ucfirst($order['status_pesanan']) }}
                                </span>
                            </td>
                            <td class="text-center">
                                 <span class="badge 
                                    @if($order['payment_status'] == 'lunas') bg-green 
                                    @else bg-red
                                    @endif
                                ">
                                    {{ Str::ucfirst(str_replace('_', ' ', $order['payment_status'])) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada data pesanan untuk periode ini.</p>
        @endif
    </div>
</body>
</html>