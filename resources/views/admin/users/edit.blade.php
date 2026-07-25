@extends('layouts.admin')

@section('title', 'Edit Akun - Admin')
@section('page_title', 'Edit Akun')
@section('page_subtitle', 'Perbarui detail informasi atau ganti hak akses.')

@section('content')
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-2xl">
        <form action="{{ route('admin.organizers.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                    Nama Organisasi / Pengguna
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                    required>
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                    Alamat Email
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                    required>
                @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Password (Opsional) --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                    Reset Password <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin diubah)</span>
                </label>
                <input type="password" name="password"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                    placeholder="Ketik password baru untuk mereset">
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
                    <option value="organizer" {{ old('role', $user->role) == 'organizer' ? 'selected' : '' }}>Organizer / HIMA
                    </option>
                    <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Superadmin
                    </option>
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection