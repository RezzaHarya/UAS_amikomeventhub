@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 bg-slate-50/50 border-b">
            <h2 class="text-2xl font-black text-slate-800">Edit Partner</h2>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Partner</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        value="{{ old('name', $partner->name) }}">
                    @error('name')
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="logo_url" class="block text-sm font-bold text-slate-700 mb-2">URL Logo (Opsional)</label>
                    <input type="url" name="logo_url" id="logo_url"
                        class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        value="{{ old('logo_url', $partner->logo_url) }}">
                    @error('logo_url')
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('admin.partners.index') }}" 
                        class="px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </a>
                    <button type="submit" 
                        class="px-6 py-3 bg-yellow-500 text-white font-bold rounded-xl hover:bg-yellow-600 transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection