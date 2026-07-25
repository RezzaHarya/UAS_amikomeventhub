@extends('layouts.app')
@section('content')

    <main class="max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-3 gap-16">
    
        {{-- ================= KOLOM KIRI (GAMBAR & PROFIL) ================= --}}
        {{-- Badge Midtrans sekarang jadi elemen flow biasa (bukan absolute) --}}
        {{-- sehingga urutannya rapi: Poster -> Badge Midtrans -> Kartu Penyelenggara --}}
        <div class="flex flex-col gap-6">
            
            {{-- Bagian Gambar --}}
            <img src="{{ asset('storage/' . $event->poster_path) }}" alt="{{ $event->title }}" class="rounded-[2rem] shadow-2xl w-full object-cover aspect-[4/5] object-center">

            {{-- Badge Midtrans --}}
            <div class="glass p-6 rounded-2xl shadow-xl border border-white bg-white/80 backdrop-blur">
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

            {{-- Organizer Profile Card --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white rounded-full shadow flex items-center justify-center font-black text-indigo-600 text-xl">
                        {{ substr($event->organizer->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Penyelenggara</p>
                        <h4 class="font-black text-lg text-indigo-900">{{ $event->organizer->name ?? 'Amikom Event' }}</h4>
                    </div>
                </div>
                
                <div class="text-left sm:text-right">
                    @php
                        $organizerRating = $event->organizer ? $event->organizer->events->flatMap->reviews->avg('rating') : 0;
                        $organizerTotalReviews = $event->organizer ? $event->organizer->events->flatMap->reviews->count() : 0;
                    @endphp
                    <div class="flex items-center sm:justify-end gap-1 text-yellow-500 font-bold text-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                        {{ number_format($organizerRating, 1) }}
                    </div>
                    <p class="text-xs text-slate-500 font-medium">Dari {{ $organizerTotalReviews }} Ulasan</p>
                </div>
            </div>

        </div>
        {{-- ================= AKHIR KOLOM KIRI ================= --}}


        {{-- ================= KOLOM KANAN (DETAIL EVENT) ================= --}}
        <div class="lg:col-span-2 space-y-12">
            
            <div class="space-y-4">
                {{-- Kategori Dinamis --}}
                <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
                    {{ $event->category->name ?? 'Event' }}
                </span>
                
                {{-- Judul Dinamis --}}
                <h1 class="text-5xl font-black">{{ $event->title }}</h1>
                
                <div class="flex flex-wrap gap-6 text-slate-500 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $event->location ?? 'Amikom' }}</span>
                    </div>
                </div>
            </div>

            <div class="prose prose-slate max-w-none">
                <h3 class="text-2xl font-bold mb-4">Deskripsi Event</h3>
                <p class="text-lg text-slate-600 leading-relaxed">
                    {{ $event->description }}
                </p>
            </div>

            {{-- Box Harga & Checkout --}}
            <div class="bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <p class="text-indigo-200 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                        <h2 class="text-5xl font-black">
                            Rp {{ number_format($event->price, 0, ',', '.') }} 
                            <span class="text-lg font-medium text-indigo-200">/orang</span>
                        </h2>
                        <p class="mt-4 text-indigo-100 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sisa stok: <span class="font-bold underline">{{ $event->stock ?? 0 }} Tiket lagi!</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('checkout.create', $event->id) }}"
                            class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xl hover:scale-105 transition-transform shadow-xl">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
                <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400 opacity-20 rounded-full"></div>
            </div>

            {{-- Kebijakan Tiket --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold">Kebijakan Tiket</h3>
                <ul class="space-y-3 text-slate-500">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tiket dapat discan di pintu masuk (Check-in).
                    </li>
                    <li class="flex items-start gap-2 text-rose-500">
                        <svg class="w-5 h-5 text-rose-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tiket yang sudah dibeli tidak dapat direfund.
                    </li>
                </ul>
            </div>

            {{-- Bagian Ulasan --}}
            <div class="mt-12 bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="flex items-center justify-between border-b pb-4 mb-6">
                    <h3 class="text-2xl font-black">Ulasan Acara</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-yellow-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </span>
                        <span class="text-xl font-bold">{{ number_format($event->average_rating, 1) }} / 5.0</span>
                    </div>
                </div>

                <div class="space-y-6 mb-10">
                    @forelse($event->reviews as $review)
                        <div class="bg-slate-50 p-6 rounded-2xl">
                            <div class="flex justify-between items-center mb-2">
                                <p class="font-bold text-slate-800">{{ $review->user ? $review->user->name : 'Anonim' }}</p>
                                <div class="flex text-yellow-400">
                                    @for($i = 0; $i < $review->rating; $i++)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-slate-600">{{ $review->comment }}</p>
                            <p class="text-xs text-slate-400 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-slate-500 italic">Belum ada ulasan untuk acara ini.</p>
                    @endforelse
                </div>

                @if(\Carbon\Carbon::parse($event->date)->isPast())
                    @auth
                        <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                            <h4 class="font-bold text-lg mb-4 text-indigo-900">Tinggalkan Ulasan Anda</h4>
                            <form action="{{ route('reviews.store', $event->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Penilaian Bintang (1-5)</label>
                                    <select name="rating" class="w-full md:w-1/3 px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-500" required>
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5) - Sangat Bagus</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5) - Bagus</option>
                                        <option value="3">⭐⭐⭐ (3/5) - Cukup</option>
                                        <option value="2">⭐⭐ (2/5) - Kurang</option>
                                        <option value="1">⭐ (1/5) - Mengecewakan</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Komentar</label>
                                    <textarea name="comment" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-indigo-500" placeholder="Bagaimana pengalaman Anda mengikuti acara ini?" required></textarea>
                                </div>
                                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition">Kirim Ulasan</button>
                            </form>
                        </div>
                    @else
                        <div class="p-4 bg-orange-50 text-orange-700 rounded-xl">
                            Silakan <a href="{{ route('google.login') }}" class="font-bold underline">Login dengan Google</a> untuk memberikan ulasan.
                        </div>
                    @endauth
                @else
                    <div class="p-4 bg-slate-100 text-slate-500 rounded-xl text-center italic">
                        Fitur ulasan akan terbuka setelah acara selesai diselenggarakan.
                    </div>
                @endif
            </div>

            <!-- Watermark -->
            <div class="text-center mt-12 mb-4 text-xs font-bold text-slate-400">
                <p>Developed by: Rezza Alfat (24.12.3314) - Universitas Amikom Yogyakarta</p>
            </div>
            
        </div>
        {{-- ================= AKHIR KOLOM KANAN ================= --}}

    </main>

@endsection