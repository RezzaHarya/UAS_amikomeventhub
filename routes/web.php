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
use App\Http\Controllers\Admin\UserController; // <-- Import UserController yang baru dibuat
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ReviewController;

// ==========================================
// RUTE USER AREA (Publik & Customer)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');
Route::get('/auth/google', [SocialiteController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);

Route::get('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);

// Rute untuk submit ulasan
Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// Lempar sembarang akses /login menuju ke login admin
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');


// ==========================================
// RUTE ADMIN & TENANT (Multi-Tenant Area)
// ==========================================
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // Rute Login & Logout dibiarkan di luar agar bisa diakses walau belum login
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // 1. RUTE KHUSUS SUPERADMIN (Contoh: Kelola Akun Organizer/User)
    Route::middleware(['auth', 'role:superadmin'])->group(function () {
        Route::resource('organizers', UserController::class);
    });

    // 2. BUNGKUSAN MIDDLEWARE: Wajib login dan harus memiliki role superadmin atau organizer (HIMA)
    Route::middleware(['auth', 'role:superadmin,organizer'])->group(function () {

        // Dashboard (Bisa diakses Superadmin maupun Organizer/HIMA)
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kelola Event 
        Route::resource('events', AdminEventController::class);

        // Kelola Kategori (Bisa dibatasi juga ke superadmin jika diinginkan, saat ini bersamaan)
        Route::resource('categories', CategoryController::class);

        // Kelola Partner
        Route::resource('partners', PartnerController::class);

        // Transaksi
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

    });
});