<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // <-- Wajib ditambahkan
use Illuminate\Support\Facades\Auth;      // <-- Wajib ditambahkan

class Event extends Model
{
    protected $fillable = [
        'category_id',
        'organizer_id',
        'title',
        'description',
        'date',
        'location',
        'price',
        'stock',
        'poster_path'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // =======================================================
    // GLOBAL SCOPE: OTOMATIS MEMFILTER DATA BERDASARKAN ROLE
    // =======================================================
    protected static function booted()
    {
        // Pastikan ada user yang sedang login
        if (Auth::check()) {
            $user = Auth::user();

            // Jika yang login BUKAN superadmin, paksa query hanya mengambil data miliknya
            if ($user->role !== 'superadmin') {
                static::addGlobalScope('organizer', function (Builder $builder) use ($user) {
                    $builder->where('organizer_id', $user->id);
                });
            }
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
}