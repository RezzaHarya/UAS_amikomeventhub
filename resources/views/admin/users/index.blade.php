@extends('layouts.admin')

@section('title', 'Kelola Organizer - Admin')
@section('page_title', 'Kelola Organizer (HIMA)')
@section('page_subtitle', 'Kelola seluruh akun penyelenggara acara dan akses superadmin.')

@section('content')
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 bg-slate-50/50 border-b flex justify-between items-center gap-4">
            <form action="{{ route('admin.organizers.index') }}" method="GET" class="flex-1 max-w-md">
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}"
                    class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
            </form>

            <a href="{{ route('admin.organizers.create') }}"
                class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Akun
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4 w-16">No</th>
                        <th class="px-8 py-4">Informasi Akun</th>
                        <th class="px-8 py-4">Peran (Role)</th>
                        <th class="px-8 py-4 w-48 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-6 font-bold text-slate-400">{{ $index + 1 }}</td>
                            
                            <td class="px-8 py-6">
                                <p class="font-black text-slate-800 text-lg">{{ $user->name }}</p>
                                <p class="text-sm font-medium text-slate-500">{{ $user->email }}</p>
                            </td>
                            
                            <td class="px-8 py-6">
                                @if($user->role === 'superadmin')
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full uppercase">Superadmin</span>
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase">Organizer</span>
                                @endif
                            </td>
                            
                            <td class="px-8 py-6">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.organizers.edit', $user->id) }}"
                                        class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>

                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.organizers.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition"
                                            title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-6 text-center text-slate-500 font-medium">
                                Belum ada data organizer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection