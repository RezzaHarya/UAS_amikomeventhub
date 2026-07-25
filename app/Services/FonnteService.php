<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public static function sendMessage($target, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Otomatis mengubah angka 0 di depan jadi +62
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Fonnte WA Error: ' . $e->getMessage());
            return false;
        }
    }
}