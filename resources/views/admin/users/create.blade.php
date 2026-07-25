@extends('layouts.admin')

@section('title', 'Tambah Akun Baru - Admin')
@section('page_title', 'Tambah Akun Baru')
@section('page_subtitle', 'Buat akun untuk Organizer (HIMA) atau Superadmin baru.')

@section('content')
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-2xl">
        <form action="{{ route('admin.organizers.store') }}" method="POST">
            @csrf

            {{-- Nama --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                    Nama Organisasi / Pengguna
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                    placeholder="Contoh: HIMA SI Amikom" required>
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                    Alamat Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                    placeholder="Contoh: himasi@amikom.ac.id" required>
                @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                    Password Sementara
                </label>
                <input type="password" name="password"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                    placeholder="Minimal 6 karakter" required minlength="6">
                @error('password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Role --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                    Hak Akses (Role)
                </label>
                <select name="role"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                    required>
                    <option value="organizer" {{ old('role') == 'organizer' ? 'selected' : '' }}>Organizer / HIMA</option>
                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                </select>
                @error('role') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-4 border-t border-slate-100 pt-6">
                <a href="{{ route('admin.organizers.index') }}"
                    class="px-6 py-4 text-slate-500 font-bold hover:text-slate-800 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                    Buat Akun
                </button>
            </div>
        </form>
    </div>
@endsection