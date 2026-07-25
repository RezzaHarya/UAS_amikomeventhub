<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    
    public function run(): void
    {
        // 1. Akun Superadmin
        \App\Models\User::create([
            'name' => 'Superadmin Amikom',
            'email' => 'superadmin@amikom.ac.id',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        // 2. Akun Organizer (HIMASI)
        $organizer = \App\Models\User::create([
            'name' => 'HIMASI Amikom',
            'email' => 'himasi@amikom.ac.id',
            'password' => bcrypt('password'),
            'role' => 'organizer',
        ]);

        // 3. Insert Kategori Event
            $category = \App\Models\Category::create([
            'name' => 'Seminar IT',
            'slug' => 'seminar-it',
        ]);

            $category2 = \App\Models\Category::firstOrCreate([
                'name' => 'Entertaiment',
                'slug' => 'entertaiment',
        ]);

            $category3 = \App\Models\Category::firstOrCreate([
                    'name' => 'Kompetisi',
                    'slug' => 'kompetisi',
            ]);
        // 3. Insert Sampel Events
            \App\Models\Event::create([
            'category_id' => $category2->id,
            'title' => 'Jazz Night 2025',
            'description' => 'Nikmati malam yang indah dengan alunan musik jazz
            yang merdu.',
            'date' => '2026-05-10 19:00:00',
            'location' => 'Amikom Baru',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-1.png',
        ]);

            \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'Hackaton - Unleash Your Inner Developer',
            'description' => 'Ayo asah skill coding kamu dan ciptakan solusi
            inovatif untuk tantangan masa depan!',
            'date' => '2026-05-05 10:00:00',
            'location' => 'Inkubator Amikom',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-2.png',
        ]);

            \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'AI & FUTURE TECH SUMMIT 2026',
            'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan
            teknologi masa depan bersama para ahli di bidangnya.',
            'date' => '2026-05-01 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-3.png',
        ]);

        // Event 4 (Tambahan Latihan: UI/UX Masterclass)
        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'UI/UX Masterclass',
            'description' => 'Pelajari cara mendesain antarmuka aplikasi yang user-friendly dan estetis langsung dari ahlinya.',
            'date' => '2026-06-15 09:00:00',
            'location' => 'Lab Komputer 4',
            'price' => 75000,
            'stock' => 40,
            'poster_path' => 'posters/uiux-masterclass.png',
        ]);

        // Event 5 (Tambahan Latihan: E-Sport U-Champ)
        \App\Models\Event::create([
            'category_id' => $category3->id,
            'title' => 'E-Sport U-Champ: Mobile Legends',
            'description' => 'Turnamen e-sport antar mahasiswa dengan total hadiah jutaan rupiah! Buktikan timmu yang terbaik.',
            'date' => '2026-06-20 10:00:00',
            'location' => 'Citra 2 Amikom',
            'price' => 100000,
            'stock' => 32,
            'poster_path' => 'posters/esport-champ.png',
        ]);

        // Event 6 (Tambahan Latihan)
        \App\Models\Event::create([
            'category_id' => $category2->id,
            'title' => 'Stand Up Comedy Campus Tour',
            'description' => 'Malam penuh tawa bersama komika-komika ternama ibukota yang mampir ke kampus kita.',
            'date' => '2026-07-05 19:30:00',
            'location' => 'Cinema 4',
            'price' => 35000,
            'stock' => 150,
            'poster_path' => 'posters/standup-tour.png',
        ]);
    }
}
