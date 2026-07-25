@extends('app')

@section('title', 'Katalog Acara')

@section('content')
<div class="space-y-12">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-10">Katalog Acara AMIKOM</h1>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex gap-4">
        <input type="text" placeholder="Cari nama acara..." class="flex-grow px-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-200 outline-none">
        <select class="px-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-200 outline-none">
            <option>Semua Kategori</option>
            <option>Seminar</option>
            <option>Workshop</option>
        </select>
        <button class="px-6 py-3 bg-sky-600 text-white rounded-xl font-semibold hover:bg-sky-700 transition">Cari</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-100">
            <img src="https://images.unsplash.com/photo-1540317580384-e5d43616b9aa?q=80&w=600&auto=format&fit=crop" alt="Seminar AI" class="w-full h-56 object-cover">
            <div class="p-8">
                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold mb-4">Seminar</span>
                <h3 class="text-2xl font-bold text-gray-950 mb-3">Seminar AI di Era Digital</h3>
                <p class="text-gray-600 text-sm mb-6">Membahas dampak dan peluang kecerdasan buatan bagi mahasiswa.</p>
                <a href="#" class="inline-block w-full text-center px-6 py-3 bg-indigo-100 text-indigo-800 rounded-xl font-semibold hover:bg-indigo-200 transition duration-300">Detail Selengkapnya</a>
            </div>
        </div>
        <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-100 hover:border-sky-300">
            <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=600&auto=format&fit=crop" alt="Workshop Python" class="w-full h-56 object-cover">
            <div class="p-8">
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold mb-4">Workshop</span>
                <h3 class="text-2xl font-bold text-gray-950 mb-3">Python untuk Analisis Data</h3>
                <p class="text-gray-600 text-sm mb-6">Penerapan Python dalam menganalisis data untuk pengambilan keputusan.</p>
                <a href="#" class="inline-block w-full text-center px-6 py-3 bg-indigo-100 text-indigo-800 rounded-xl font-semibold hover:bg-indigo-200 transition duration-300">Detail Selengkapnya</a>
            </div>
        </div>
        <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-100">
            <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=600&auto=format&fit=crop" alt="Kompetisi Startup" class="w-full h-56 object-cover">
            <div class="p-8">
                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold mb-4">Kompetisi</span>
                <h3 class="text-2xl font-bold text-gray-950 mb-3">AMIKOM Startup Challenge</h3>
                <p class="text-gray-600 text-sm mb-6">Kompetisi ide bisnis startup untuk mahasiswa AMIKOM.</p>
                <a href="#" class="inline-block w-full text-center px-6 py-3 bg-indigo-100 text-indigo-800 rounded-xl font-semibold hover:bg-indigo-200 transition duration-300">Detail Selengkapnya</a>
            </div>
        </div>
        </div>
</div>
@endsection