<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // INI ADALAH FUNGSI INDEX YANG DICARI OLEH LARAVEL
    public function index(Request $request)
    {
        // Mulai query ke tabel Category
        $query = Category::query();

        // Jika user mengetik sesuatu di kotak pencarian
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Ambil datanya (ditambah urutan terbaru biar rapi)
        $categories = $query->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        // Mengarahkan ke halaman form tambah kategori
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi data (pastikan nama tidak kosong)
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // 2. Simpan data ke database
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // 3. Kembalikan ke halaman index dengan pesan sukses
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cari data dan update
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Menghapus data dari database
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}