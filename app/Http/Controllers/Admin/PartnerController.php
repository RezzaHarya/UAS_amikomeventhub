<?php

namespace App\Http\Controllers\Admin;

use App\Models\Partner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Wajib tambahkan ini untuk kelola file

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::all();
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        // Validasi diubah menjadi image
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $logoPath = null;
        // Jika ada file yang diupload, simpan ke storage
        if ($request->hasFile('logo_url')) {
            $logoPath = $request->file('logo_url')->store('partners', 'public');
        }

        Partner::create([
            'name' => $request->name,
            'logo_url' => $logoPath,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, string $id)
    {
        // Validasi diubah menjadi image
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $partner = Partner::findOrFail($id);
        $logoPath = $partner->logo_url; // Simpan path lama sebagai default

        // Jika ada file baru yang diupload
        if ($request->hasFile('logo_url')) {
            // Hapus gambar lama dari storage jika ada
            if ($partner->logo_url && Storage::disk('public')->exists($partner->logo_url)) {
                Storage::disk('public')->delete($partner->logo_url);
            }
            // Simpan gambar baru
            $logoPath = $request->file('logo_url')->store('partners', 'public');
        }

        $partner->update([
            'name' => $request->name,
            'logo_url' => $logoPath,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $partner = Partner::findOrFail($id);

        // Hapus gambar dari storage sebelum menghapus data dari database
        if ($partner->logo_url && Storage::disk('public')->exists($partner->logo_url)) {
            Storage::disk('public')->delete($partner->logo_url);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}