<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Buyer\CatalogController;
use App\Http\Controllers\Buyer\PreorderController;
use App\Http\Controllers\Buyer\SupportMessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. HALAMAN BERANDA / LANDING
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. OTENTIKASI (LOGIN & REGISTER)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// 3. ALUR BUYER (KATALOG, PRE-ORDER & CS) - WAJIB LOGIN
// Guest hanya bisa membuka halaman welcome; akses katalog dsb. diarahkan ke form login.
Route::middleware('auth')->group(function () {
    // Katalog & Showcase
    Route::get('/katalog', [CatalogController::class, 'index'])->name('katalog');
    Route::get('/fandom/{slug}', [CatalogController::class, 'fandom'])->name('fandom.detail');
    Route::get('/showcase/{character?}', [CatalogController::class, 'characterShowcase'])->name('character.showcase');

    // 1. Fitur Profil User (Buyer & Creator)
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    // 2. Alur Khusus Buyer
    Route::get('/my-orders', [PreorderController::class, 'index'])->name('buyer.orders'); // <-- Tambahkan baris ini
    Route::get('/preorder/{merchandise}', [PreorderController::class, 'create'])->name('preorder.create');
    Route::post('/preorder/{merchandise}', [PreorderController::class, 'store'])->name('preorder.store');

    Route::get('/bantuan', [SupportMessageController::class, 'create'])->name('support.create');
    Route::post('/bantuan', [SupportMessageController::class, 'store'])->name('support.store');
});

// 6. PANEL ADMIN / CREATOR (BENTO DASHBOARD & CRUD)
Route::middleware(['auth', 'role:creator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/preorders', [DashboardController::class, 'preorders'])->name('preorders');
    Route::patch('/orders/{id}/status', [DashboardController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{id}', [DashboardController::class, 'destroyOrder'])->name('orders.destroy');

    Route::get('/merchandises', [DashboardController::class, 'merchandises'])->name('merchandises');
    Route::post('/merchandises', [DashboardController::class, 'storeMerchandise'])->name('merchandises.store');
    Route::put('/merchandises/{merchandise}', [DashboardController::class, 'updateMerchandise'])->name('merchandises.update');
    Route::delete('/merchandises/{id}', [DashboardController::class, 'destroyMerchandise'])->name('merchandises.destroy');
    Route::post('/categories', [DashboardController::class, 'storeCategory'])->name('categories.store');

    Route::get('/support', [DashboardController::class, 'support'])->name('support');
    Route::patch('/support/{id}/reply', [DashboardController::class, 'replySupport'])->name('support.reply');
    // 5. Manajemen Sewa Booth Pameran
    Route::get('/booth-rentals', [DashboardController::class, 'boothRentals'])->name('booths');
    Route::post('/booth-rentals/{id}/book', [DashboardController::class, 'bookBooth'])->name('booths.book');
    // 6. Menu Baru: Riwayat Transaksi Finansial
    Route::get('/transactions', [DashboardController::class, 'transactions'])->name('transactions');
});
