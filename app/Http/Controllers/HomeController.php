<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; // 1. Import Model Category
use App\Models\Partner;  // 2. Import Model Partner
use App\Models\Event;    // 3. Import Model Event

class HomeController extends Controller
{
    public function index()
    {
        // Menghapus take(3) agar mengambil SELURUH data event
        // Tetap menggunakan latest() agar event terbaru muncul paling atas
        $events = Event::latest()->get();
        
        $categories = Category::all();
        $partners = Partner::all();

        // 4. Kirim data tersebut ke view homepage (welcome.blade.php)
        return view('welcome', compact('categories', 'partners', 'events'));
    }
}