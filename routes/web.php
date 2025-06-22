<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PantiAsuhanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route untuk halaman utama (welcome page)
Route::get('/', function () {
    return view('welcome');
});

// Grup Route yang memerlukan autentikasi dan verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {
    // Route Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route untuk Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk melihat detail panti asuhan
    Route::get('/panti-asuhan/{pantiAsuhan}', [PantiAsuhanController::class, 'show'])->name('panti.show');

    // Route untuk pembayaran donasi
    Route::post('/donasi', [TransaksiController::class, 'createDonation'])->name('donasi.create');

    // Halaman Riwayat Donasi User yang sedang login (Donatur)
    Route::get('/riwayat-donasi', [TransaksiController::class, 'userDonationHistory'])->name('donasi.riwayat');

    // Halaman Donasi Diterima (Khusus Panti)
    Route::get('/panti/donasi-diterima', [TransaksiController::class, 'pantiDonationHistory'])
        ->middleware('panti') // Hanya user dengan role 'panti' yang bisa akses
        ->name('panti.donasi.diterima');

    // Halaman Customer Service / Pesan untuk user (Donatur/Panti)
    Route::get('/pesan', [MessageController::class, 'index'])->name('message.index');
    Route::post('/pesan', [MessageController::class, 'store'])->name('message.store');
    Route::get('/pesan/{id}', [MessageController::class, 'show'])->name('message.show'); // Untuk detail pesan user

    // Grup Route khusus untuk Admin dengan middleware 'admin'
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Manajemen Pesan (Admin)
        Route::get('/', [MessageController::class, 'adminIndex'])->name('admin.messages.index'); // Halaman utama admin untuk pesan
        Route::get('/{id}', [MessageController::class, 'show'])->name('admin.messages.show'); // Detail pesan untuk admin (reuse show method)
        Route::put('/pesan/{id}/reply', [MessageController::class, 'reply'])->name('admin.messages.reply'); // Balas pesan

        // Manajemen User (CRUD)
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // Manajemen Panti Asuhan (CRUD)
        Route::get('/panti', [PantiAsuhanController::class, 'index'])->name('admin.panti.index');
        Route::get('/panti/create', [PantiAsuhanController::class, 'create'])->name('admin.panti.create');
        Route::post('/panti', [PantiAsuhanController::class, 'store'])->name('admin.panti.store');
        Route::get('/panti/{panti}/edit', [PantiAsuhanController::class, 'edit'])->name('admin.panti.edit');
        Route::put('/panti/{panti}', [PantiAsuhanController::class, 'update'])->name('admin.panti.update');
        Route::delete('/panti/{panti}', [PantiAsuhanController::class, 'destroy'])->name('admin.panti.destroy');

        // Manajemen Transaksi (Admin)
        Route::get('/transaksi', [TransaksiController::class, 'adminTransactionHistory'])->name('admin.transaksi.index');
        Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('admin.transaksi.show'); // Detail transaksi untuk admin
        Route::put('/transaksi/{id}/update-status', [TransaksiController::class, 'updateTransactionStatus'])->name('admin.transaksi.update-status');
    });
});

// Route autentikasi (login, register, dll.)
require __DIR__.'/auth.php';