@extends('app')

@section('title', 'Profil Praktikan')

@section('content')
<div class="max-w-4xl mx-auto space-y-12">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-10">Profil Praktikan</h1>

    <div class="bg-white p-12 rounded-3xl shadow-sm border border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-10 items-center hover:shadow-lg transition">
        <div class="mx-auto flex items-center justify-center w-40 h-40 bg-indigo-100 rounded-full shadow-inner">
            <svg class="w-20 h-20 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
        </div>
        <div class="md:col-span-2 space-y-4">
            <p class="text-sm text-gray-400 font-medium">Data Diri Mahasiswa</p>
            <h2 class="text-3xl font-bold text-gray-950">Rezza Alfat Harya sambada</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-600">
                <p>NIM: <span class="font-medium text-gray-800">24.12.3314</span></p>
                <p>Prodi: <span class="font-medium text-gray-800">S1 Sistem Informasi</span></p>
                <p>Fakultas: <span class="font-medium text-gray-800">Ilmu Komputer</span></p>
                <p>Status: <span class="inline-block px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Aktif</span></p>
            </div>
        </div>
    </div>

    <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-xl font-semibold mb-6">Aktivitas Mahasiswa</h3>
        <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
        <div class="mt-6 flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-slate-100 rounded-full text-xs text-slate-700">UI/UX</span>
            <span class="px-3 py-1 bg-slate-100 rounded-full text-xs text-slate-700">Frontend Dev</span>
            <span class="px-3 py-1 bg-slate-100 rounded-full text-xs text-slate-700">Event Organizer</span>
        </div>
    </div>
</div>
@endsection