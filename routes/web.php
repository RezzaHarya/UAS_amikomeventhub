<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ReviewController;

// Rute User Area (Tetap dibiarkan terbuka untuk pengunjung)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');
Route::get('/auth/google', [SocialiteController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);

Route::get('/checkout/{event}',   [App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}',  [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);

// Rute untuk submit ulasan
Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// Lempar sembarang akses /login menuju ke login admin [cite: 1691-1693]
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Rute Admin [cite: 1694-1695]
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // Rute Login & Logout dibiarkan di luar agar bisa diakses walau belum login [cite: 1696-1699]
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    

    // BUNGKUSAN MIDDLEWARE: Area terlarang, wajib login dan role admin [cite: 1700]
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // --- SEMUA KODE ADMIN LAMA KAMU MASUK KE SINI ---

        // Dashboard Admin (URL: http://127.0.0.1:8000/admin)
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard'); 
        
        // Kelola Event Admin (URL: http://127.0.0.1:8000/admin/events)
        Route::resource('events', AdminEventController::class);

        // Kelola Kategori (URL: http://127.0.0.1:8000/admin/categories)
        Route::resource('categories', CategoryController::class);
        
        // Kelola Partner
        Route::resource('partners', PartnerController::class);

        // Transaksi
        Route::get('transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');

        //simpan transaksi
       
    });
});