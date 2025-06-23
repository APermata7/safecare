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

    // admin routes only
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/', function () {
            return view('admin.messages');
        })->name('admin');

        Route::get('/users', function () {
            return view('admin.users');
        })->name('admin.users');

        Route::get('/panti', function () {
            return view('admin.panti');
        })->name('admin.panti');

        Route::get('/transaksi', function () {
            return view('admin.transaksi');
        })->name('admin.transaksi');

        Route::get('/api', [MessageController::class, 'adminIndex'])->name('adminApi');
        Route::get('/{id}', [MessageController::class, 'show'])->name('admin.show');
        Route::put('/{id}/reply', [MessageController::class, 'reply'])->name('admin.reply');

        Route::get('/users/api', [UserController::class, 'index']);
        Route::put('/users/{id}/ban', [UserController::class, 'ban'])->name('ban');
        Route::put('/users/{id}/unban', [UserController::class, 'unban'])->name('unban');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('delete.user');

        Route::get('/client', [UserController::class, 'getDonatorOnly'])->name('admin.getDonator');
        Route::get('/client/panti', [UserController::class, 'getPantiAsuhanList'])->name('get.panti');
        Route::put('/client/{id}/role', [UserController::class, 'updateRole'])->name('update.role');

        Route::get('/panti/api', [PantiAsuhanController::class, 'indexAdmin'])->name('admin.panti.index');
        Route::get('/panti/{id}', [PantiAsuhanController::class, 'showAdmin'])->name('admin.panti.show');
        Route::put('/panti/{id}', [PantiAsuhanController::class, 'update'])->name('admin.panti.update');

        Route::get('/transaksi/api', [TransaksiController::class, 'adminTransactionHistory']);
        Route::get('/transaksi/{id}', [TransaksiController::class, 'showDetail']);
        Route::put('/transaksi/{id}', [TransaksiController::class, 'update']);
    });
});

require __DIR__.'/auth.php';