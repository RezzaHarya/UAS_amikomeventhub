@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 bg-slate-50/50 border-b">
            <h2 class="text-2xl font-black text-slate-800">Tambah Kategori Baru</h2>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf <div class="mb-6">
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="Contoh: Konser, Seminar, Workshop" value="{{ old('name') }}">
                    
                    @error('name')
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('admin.categories.index') }}" 
                        class="px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </a>
                    
                    <button type="submit" 
                        class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection