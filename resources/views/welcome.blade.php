@extends('layouts.app')

@section('content')

    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">#1 Event Platform</span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#events" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                    Mulai Jelajah
                </a>
                <a href="#" class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">
                    Cara Pesan
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <img src="assets/concert.png" alt="Concert" class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">

            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white bg-white/80 backdrop-blur">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase">Terverifikasi</p>
                        <p class="font-bold">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 pt-10">
        <div class="mb-8">
            <h2 class="text-2xl font-extrabold mb-2">Jelajahi Kategori</h2>
            <p class="text-slate-500 font-medium">Temukan event berdasarkan minatmu</p>
        </div>
        
        <div class="flex flex-wrap gap-4">
            @foreach($categories as $category)
            <div class="px-6 py-3 bg-white border border-slate-200 rounded-2xl shadow-sm hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md transition cursor-pointer font-bold text-slate-700">
                {{ $category->name }}
            </div>
            @endforeach
        </div>
    </section>

    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Event Terdekat</h2>
                <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
            </div>
            <div class="flex gap-2">
                <button class="p-3 border rounded-xl hover:bg-white hover:shadow-md transition font-bold text-slate-600">Semua Event</button>
            </div>
        </div>

        {{-- KANDANG GRID: Pembagi 3 Kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- LOOPING DATA DARI DATABASE --}}
            @foreach($events as $event)
            <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col">
                
                {{-- KOTAK GAMBAR --}}
                <div class="relative w-full aspect-[3/4] overflow-hidden bg-slate-50">
                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/concert.png') }}" 
                        alt="{{ $event->title }}" 
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                        {{ $event->category->name ?? 'Kategori' }}
                    </div>
                </div>
                
                {{-- KOTAK KONTEN BAWAH --}}
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100 mt-auto">
                        <span class="text-xl font-black text-indigo-600">
                            {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                        </span>
                        
                        <a href="{{ route('events.show', $event->id) }}" class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">
                            Detail
                        </a>
                    </div>
                </div>
                
            </div>
            @endforeach

        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 pb-24 border-t border-slate-100 pt-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold mb-2">Partner Pendukung</h2>
            <p class="text-slate-500 font-medium">Platform ini didukung oleh berbagai instansi dan perusahaan ternama</p>
        </div>

        <div class="flex flex-wrap justify-center gap-8 md:gap-12 items-center">
            @foreach($partners as $partner)
            <div class="group flex flex-col items-center gap-3">
                <div class="w-32 h-16 md:w-40 md:h-20 bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-center hover:shadow-lg hover:border-indigo-200 transition duration-300">
                    @if($partner->logo_url)
                        <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-h-full max-w-full object-contain filter grayscale group-hover:grayscale-0 transition duration-300">
                    @else
                        <span class="text-slate-400 font-bold text-xs tracking-wider uppercase text-center">{{ $partner->name }}</span>
                    @endif
                </div>
                <p class="text-sm font-bold text-slate-500 group-hover:text-indigo-600 transition">{{ $partner->name }}</p>
            </div>
            @endforeach
        </div>
    </section>

@endsection