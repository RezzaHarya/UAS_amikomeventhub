@extends('layouts.admin')
@section('content')

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 bg-slate-50/50 border-b flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[300px] flex gap-2">
                    <input type="text" placeholder="Cari Order ID, Nama, atau Email..."
                        class="flex-1 px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition uppercase text-sm font-medium tracking-wide">
                </div>
                <div class="flex gap-2">
                    <select
                        class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none text-sm font-bold">
                        <option>Semua Status</option>
                        <option class="text-green-600">Success</option>
                        <option class="text-orange-600">Pending</option>
                        <option class="text-rose-600">Expired</option>
                    </select>
                    <select
                        class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none text-sm font-bold">
                        <option>Bulan Ini</option>
                        <option>Bulan Lalu</option>
                        <option>Tahun 2024</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
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
                        @foreach($transactions as $transaction)
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
                                    {{-- Jika ada relasi ke tabel events, gunakan kode di bawah ini: --}}
                                    {{ $transaction->event->title ?? 'Event Dihapus/Tidak Ditemukan' }}
                                </p>
                            </td>

                            {{-- Kolom Tanggal Transaksi --}}
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}
                            </td>

                            {{-- Kolom Status --}}
                            <td class="px-6 py-4">
                                {{-- Bisa dikasih pewarnaan kondisi logika nanti, sementara cetak teksnya dulu --}}
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    {{ $transaction->status == 'Pending' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600' }}">
                                    {{ strtoupper($transaction->status) }}
                                </span>
                            </td>

                            {{-- Kolom Total Tagihan --}}
                            <td class="px-6 py-4 font-bold text-slate-700">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-6 bg-slate-50/50 border-t flex justify-between items-center">
                <p class="text-sm text-slate-500 font-medium">Menampilkan 3 dari 124 transaksi</p>
                <div class="flex gap-2">
                    <button
                        class="px-4 py-2 border rounded-xl hover:bg-white transition text-sm font-bold opacity-50 cursor-not-allowed">Previous</button>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl shadow-md text-sm font-bold">1</button>
                    <button class="px-4 py-2 border rounded-xl hover:bg-white transition text-sm font-bold">2</button>
                    <button
                        class="px-4 py-2 border rounded-xl hover:bg-white transition text-sm font-bold">Next</button>
                </div>
            </div>
        </div>


    @endsection