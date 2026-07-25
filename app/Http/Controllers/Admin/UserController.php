<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Menampilkan daftar semua organizer atau user
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Menampilkan form untuk menambah organizer baru
    public function create()
    {
        return view('admin.users.create');
    }

    // Menyimpan data organizer baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:superadmin,organizer',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.organizers.index')->with('success', 'Organizer baru berhasil ditambahkan.');
    }

    // Menampilkan form edit data organizer
    // Menampilkan form edit data organizer
    public function edit($id)
    {
        // Cari user berdasarkan ID, jika tidak ada akan otomatis error 404
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    // Mengupdate data organizer (termasuk opsi reset password jika diisi)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:superadmin,organizer',
            'password' => 'nullable|string|min:6', // Password opsional saat edit
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Jika kolom password diisi, enkripsi dan update password baru
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.organizers.index')->with('success', 'Data Organizer berhasil diperbarui.');
    }

    // Menghapus akun organizer
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Pengaman: Mencegah superadmin menghapus akunnya sendiri yang sedang aktif login
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Aksi ditolak! Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.organizers.index')->with('success', 'Akun Organizer berhasil dihapus.');
    }
}