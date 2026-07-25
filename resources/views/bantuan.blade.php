@extends('app')

@section('title', 'Bantuan')

@section('content')
<div class="max-w-4xl mx-auto space-y-12">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-10">Pusat Bantuan AmikomEventHub</h1>

    <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 space-y-8 hover:shadow-lg transition">
        <h2 class="text-2xl font-bold text-gray-950">Pertanyaan yang Sering Diajukan (FAQ)</h2>
        
        <div class="border-t border-gray-100 pt-6 space-y-6">
            <div class="p-6 bg-slate-50 rounded-xl border border-gray-100 hover:border-sky-200 transition">
                <h3 class="font-semibold text-gray-900 mb-2">Q: Bagaimana cara mendaftar acara?</h3>
                <p class="text-gray-600 text-sm">A: Anda dapat mengklik tombol "Detail Selengkapnya" pada acara yang diinginkan di Katalog, kemudian ikuti instruksi pendaftaran yang tertera.</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-xl border border-gray-100 hover:border-sky-200 transition">
                <h3 class="font-semibold text-gray-900 mb-2">Q: Apakah semua acara gratis?</h3>
                <p class="text-gray-600 text-sm">A: Sebagian besar acara adalah gratis, namun ada beberapa acara yang memerlukan biaya pendaftaran. Informasi biaya akan tertera pada detail acara.</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-xl border border-gray-100 hover:border-sky-200 transition">
                <h3 class="font-semibold text-gray-900 mb-2">Q: Di mana saya bisa menemukan lokasi acara?</h3>
                <p class="text-gray-600 text-sm">A: Lokasi acara akan tertera pada detail setiap acara di Katalog.</p>
            </div>
        </div>
    </div>
</div>
@endsection