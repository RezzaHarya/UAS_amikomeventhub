@extends('layouts.admin')
@section('content')

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">

        {{-- 1. Bungkus dengan FORM menggunakan method GET --}}
        <form method="GET" action="{{ route('admin.transactions.index') }}"
            class="px-8 py-6 bg-slate-50/50 border-b flex flex-wrap gap-4 items-center">

            <div class="flex-1 min-w-[300px] flex gap-2">
                {{-- Tambahkan name="search" dan value bawaan dari request --}}
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Order ID, Nama, atau Email..."
                    class="flex-1 px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium tracking-wide">

                {{-- Tombol submit untuk trigger pencarian --}}
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
                    Cari
                </button>
            </div>

            <div class="flex gap-2">
                {{-- Tambahkan name="status" dan event onchange agar otomatis tersubmit saat dipilih --}}
                <select name="status" onchange="this.form.submit()"
                    class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none text-sm font-bold">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }} class="text-green-600">
                        Success</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="text-orange-600">
                        Pending</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }} class="text-rose-600">
                        Expired</option>
                </select>

                {{-- Tambahkan name="date" --}}
                <select name="date" onchange="this.form.submit()"
                    class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none text-sm font-bold">
                    <option value="" {{ request('date') == '' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="bulan_ini" {{ request('date') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="bulan_lalu" {{ request('date') == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                </select>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <!-- (Bagian <thead> Anda tetap sama) -->
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Order ID</th>
                        <th class="px-8 py-4">Detail Pembeli</th>
                        <th class="px-8 py-4">Event</th>
                        <th class="px-8 py-4">Tgl Transaksi</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">

                            {{-- Kolom Order ID --}}
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg">
                                    #{{ $transaction->order_id }}
                                </span>
                            </td>

                            {{-- Kolom Detail Pembeli --}}
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-700">{{ $transaction->customer_name }}</p>
                                <p class="text-xs text-slate-500">{{ $transaction->customer_email }}</p>
                            </td>

                            {{-- Kolom Nama Event --}}
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-700">
                                    {{ $transaction->event->title ?? 'Event Dihapus/Tidak Ditemukan' }}
                                </p>
                            </td>

                            {{-- Kolom Tanggal Transaksi --}}
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}
                            </td>

                            {{-- Kolom Status --}}
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs font-bold rounded-full 
                                {{ strtolower($transaction->status) == 'pending' ? 'bg-orange-100 text-orange-600' : (strtolower($transaction->status) == 'expired' ? 'bg-rose-100 text-rose-600' : 'bg-green-100 text-green-600') }}">
                                    {{ strtoupper($transaction->status) }}
                                </span>
                            </td>

                            {{-- Kolom Total Tagihan --}}
                            <td class="px-6 py-4 font-bold text-slate-700">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-500 font-medium">
                                Data transaksi tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 2. Ganti Footer Paginasi Statis dengan Dinamis --}}
        <div class="px-8 py-6 bg-slate-50/50 border-t flex flex-wrap justify-between items-center gap-4">

            <p class="text-sm text-slate-500 font-medium">
                Menampilkan {{ $transactions->count() }} dari {{ $transactions->total() }} transaksi
            </p>

            @if ($transactions->hasPages())
                <div class="flex gap-2">

                    @foreach ($transactions->withQueryString()->linkCollection() as $link)

                        @if ($loop->first)
                            @if ($link['url'])
                                <a href="{{ $link['url'] }}"
                                    class="px-4 py-2 border rounded-xl text-slate-700 hover:bg-white transition text-sm font-bold">Previous</a>
                            @else
                                <button
                                    class="px-4 py-2 border rounded-xl bg-white text-slate-500 text-sm font-bold opacity-50 cursor-not-allowed"
                                    disabled>Previous</button>
                            @endif

                        @elseif ($loop->last)
                            @if ($link['url'])
                                <a href="{{ $link['url'] }}"
                                    class="px-4 py-2 border rounded-xl text-slate-700 hover:bg-white transition text-sm font-bold">Next</a>
                            @else
                                <button
                                    class="px-4 py-2 border rounded-xl bg-white text-slate-500 text-sm font-bold opacity-50 cursor-not-allowed"
                                    disabled>Next</button>
                            @endif

                        @else
                            @if ($link['url'])
                                @if ($link['active'])
                                    <button
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-xl shadow-md text-sm font-bold">{{ $link['label'] }}</button>
                                @else
                                    <a href="{{ $link['url'] }}"
                                        class="px-4 py-2 border rounded-xl text-slate-700 hover:bg-white transition text-sm font-bold">{{ $link['label'] }}</a>
                                @endif
                            @else
                                <button
                                    class="px-4 py-2 border rounded-xl bg-white text-slate-500 text-sm font-bold opacity-50 cursor-not-allowed"
                                    disabled>{{ $link['label'] }}</button>
                            @endif
                        @endif

                    @endforeach
                </div>
            @endif
        </div>

    </div>

@endsection