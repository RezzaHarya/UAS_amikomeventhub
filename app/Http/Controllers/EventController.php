<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // Method untuk menampilkan halaman detail event
    public function show($id)
    {
        $event = Event::findOrFail($id); 

        // 2. Tampilkan view 'event-detail' dan bawa data event tersebut
        return view('event-detail', compact('event'));
    }

    // Method untuk menampilkan halaman checkout
    public function checkout()
    {
        return view('checkout');
    }

    // Method untuk menampilkan halaman tiket
    public function ticket()
    {
        return view('ticket');
    }

}