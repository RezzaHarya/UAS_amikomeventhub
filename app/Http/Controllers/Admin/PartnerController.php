<?php

namespace App\Http\Controllers\Admin;

use App\Models\Partner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $partners = $query->latest()->get();

        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $logoPath = null;

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
    }

    public function edit(string $id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $partner = Partner::findOrFail($id);
        $logoPath = $partner->logo_url;

        if ($request->hasFile('logo_url')) {
            if ($partner->logo_url && Storage::disk('public')->exists($partner->logo_url)) {
                Storage::disk('public')->delete($partner->logo_url);
            }
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

        if ($partner->logo_url && Storage::disk('public')->exists($partner->logo_url)) {
            Storage::disk('public')->delete($partner->logo_url);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}