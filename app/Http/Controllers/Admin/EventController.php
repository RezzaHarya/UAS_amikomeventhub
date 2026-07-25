<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $query = Event::with(['category', 'organizer']);
        
        // Jika bukan superadmin, filter hanya event milik organizer yang sedang login
        if (Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin') {
            $query->where('organizer_id', Auth::id());
        }
        
        $events = $query->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Tangkap dan validasi semua data dari form (Ini yang sebelumnya kurang)
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Tambahkan ID Penyelenggara yang sedang login
        $data['organizer_id'] = Auth::id();

        // 3. Proses upload poster jika ada
        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        // 4. Simpan ke database
        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        // Validasi data
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ganti gambar jika ada yang baru di-upload
        if ($request->hasFile('poster')) {
            // Hapus gambar lama
            if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
                Storage::disk('public')->delete($event->poster_path);
            }
            // Simpan gambar baru
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil diupdate.');
    }

    public function destroy(Event $event) 
    {
        // Hapus gambar dari storage jika ada
        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();
        
        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil dihapus.');
    }
}