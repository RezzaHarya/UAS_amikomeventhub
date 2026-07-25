@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Ringkasan')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pendapatan</p>
            <h3 class="text-2xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                    </path>
                </svg>
            </div>
            <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
            <h3 class="text-2xl font-black">{{ number_format($ticketsSold, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-slate-400 text-sm font-bold uppercase mb-1">Event Aktif</p>
            <h3 class="text-2xl font-black">{{ $activeEvents }} Event</h3>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-slate-400 text-sm font-bold uppercase mb-1">Pesanan Pending</p>
            <h3 class="text-2xl font-black">{{ $pendingOrders }} Pesanan</h3>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b flex justify-between items-center">
            <h3 class="font-black text-xl">Transaksi Terakhir</h3>
            <a href="{{ route('admin.transactions.index') }}" class="text-indigo-600 font-bold hover:underline">Lihat
                Semua</a>
        </div>

        <div class="p-0">
            <table class="w-full text-left border-collapse">
                <tbody>
                    @forelse($recentTransactions as $trx)
                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition">
                            <td class="p-4 pl-8">
                                <p class="font-bold text-slate-800">{{ $trx->event->title ?? 'Event Dihapus' }}</p>
                                <p class="text-xs font-medium text-slate-400">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                            </td>
                            <td class="p-4 text-right pr-8 flex flex-col items-end gap-1">
                                <p class="font-bold text-indigo-600">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase 
                                            {{ $trx->status == 'success' || $trx->status == 'settlement' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-8 text-center font-medium text-slate-400">
                                Belum ada data transaksi masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grafik Tren Penjualan -->
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm mb-10">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-black text-xl text-slate-800">Tren Pendapatan</h3>
                <p class="text-sm text-slate-400 font-medium">7 Hari Terakhir</p>
            </div>
        </div>

        <!-- Tempat grafik dirender -->
        <div class="relative h-72 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{--
    Data chart ditaruh di sini sebagai JSON murni (type="application/json"),
    BUKAN dieksekusi sebagai JavaScript. Ini memisahkan syntax Blade dari kode JS,
    supaya tidak ada lagi campur aduk PHP+JS yang bikin editor/linter bingung
    dan supaya datanya aman walau $chartDates / $chartTotals kosong.
    --}}
    <script id="chart-data" type="application/json">
            {!! json_encode([
        'labels' => $chartDates ?? [],
        'data' => $chartTotals ?? [],
    ]) !!}
        </script>

    <!-- Memuat library Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('revenueChart').getContext('2d');

            // Ambil JSON dari tag <script type="application/json"> di atas, lalu parse dengan JSON.parse
            // supaya benar-benar valid JavaScript object, bukan sekadar teks hasil replace Blade.
            const chartDataRaw = document.getElementById('chart-data').textContent;
            const parsedChart = JSON.parse(chartDataRaw);

            const chartLabels = parsedChart.labels;
            const chartData = parsedChart.data;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels.length > 0 ? chartLabels : ['Belum ada data'],
                    datasets: [{
                        label: 'Pendapatan Harian (Rp)',
                        data: chartData.length > 0 ? chartData : [0],
                        borderColor: '#4f46e5', // Warna garis Indigo-600 Tailwind
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4 // Membuat garis melengkung (smooth)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Sembunyikan legenda agar lebih bersih
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 14, family: "'Plus Jakarta Sans', sans-serif" },
                            bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false,
                            },
                            ticks: {
                                font: { family: "'Plus Jakarta Sans', sans-serif" },
                                color: '#94a3b8',
                                callback: function (value, index, values) {
                                    if (value === 0) return '0';
                                    // Menyingkat angka (Contoh: 1000000 menjadi 1M)
                                    return 'Rp ' + (value / 1000) + 'k';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                font: { family: "'Plus Jakarta Sans', sans-serif" },
                                color: '#94a3b8'
                            }
                        }
                    }
                }
            });
        });
    </script>

    <!-- Watermark Identitas -->
    <div class="text-center mt-12 mb-4 text-xs font-bold text-slate-400">
        <p>Developed by: Rezza Alfat (24.12.3314) - Universitas Amikom Yogyakarta</p>
    </div>
@endsection