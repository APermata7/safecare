<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PantiAsuhanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PantiAsuhanController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/panti-asuhan', [PantiAsuhanController::class, 'index']);
    Route::get('/panti-asuhan/{id}', [PantiAsuhanController::class, 'show'])->name('panti.show');
    Route::get('/panti-asuhan/{id}/history', [TransaksiController::class, 'showHistoryPanti'])->name('panti.history');
    Route::post('/donasi', [TransaksiController::class, 'createDonation'])->name('donasi.create');

    // halaman untuk pengguna mengirim message ke admin
    Route::get('/pesan', [MessageController::class, 'index']);
    Route::get('/pesan/{id}', [MessageController::class, 'show']);
    Route::post('/pesan', [MessageController::class, 'store']);

    // halaman riwayat donasi yang telah dilakukan oleh donatur
    Route::get('/riwayat-donasi', [TransaksiController::class, 'userDonationHistory'])->name('donasi.riwayat');

    Route::get('/customerservice', function () {
        return view('messages.index');
    })->name('customer.service');

    // halaman untuk melihat riwayat transaksi donasi yang telah diterima panti asuhan
    Route::middleware('panti')->prefix('panti')->group(function () {
        Route::get('/donasi-diterima', [TransaksiController::class, 'pantiDonationHistory'])->name('panti.donasi.diterima');
    });

    // user management for admin only
    Route::middleware('admin')->prefix('admin')->group(function () {
        // halaman /admin untuk melihat pesan masuk dari donatur/panti untuk admin
        Route::get('/', function () {
            return view('admin.messages.index');
        })->name('admin');

        Route::get('/api', [MessageController::class, 'adminIndex'])->name('adminApi');
        Route::get('/{id}', [MessageController::class, 'show'])->name('admin.show');
        Route::put('/{id}/reply', [MessageController::class, 'reply'])->name('admin.reply');

        // halaman /admin/users, management user
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::put('/users/{id}/ban', [UserController::class, 'ban'])->name('ban');
        Route::put('/users/{id}/unban', [UserController::class, 'unban'])->name('unban');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('delete.user');

        // halaman /admin/client untuk update donatur ke panti
        // sekali menjadi panti, tidak bisa kembali menjadi role donatur
        Route::get('/client', [UserController::class, 'getDonatorOnly'])->name('admin.getDonator');
        Route::get('/client/panti', [UserController::class, 'getPantiAsuhanList'])->name('get.panti');
        Route::put('/client/{id}/role', [UserController::class, 'updateRole'])->name('update.role');

        // halaman untuk admin manajemen panti asuhan
        Route::get('/panti', [PantiAsuhanController::class, 'indexAdmin'])->name('admin.panti.index');
        Route::post('/panti', [PantiAsuhanController::class, 'store'])->name('admin.panti.store');
        Route::get('/panti/{id}', [PantiAsuhanController::class, 'showAdmin'])->name('admin.panti.show');
        Route::put('/panti/{id}', [PantiAsuhanController::class, 'update'])->name('admin.panti.update');
    });
});

require __DIR__.'/auth.php';