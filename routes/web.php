<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PantiAsuhanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPantiController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard utama (untuk semua role)
    Route::get('/dashboard', [PantiAsuhanController::class, 'index'])
        ->name('dashboard');

    // Profile routes (untuk semua user)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Detail panti (untuk semua user)
    Route::get('/panti-asuhan/{id}', [PantiAsuhanController::class, 'show'])->name('panti.show');
    Route::post('/donasi', [TransaksiController::class, 'createDonation'])->name('donasi.create');

    // Message routes
    Route::get('/pesan', [MessageController::class, 'index'])->name('pesan.index');
    Route::get('/pesan/{id}', [MessageController::class, 'show'])->name('pesan.show');
    Route::post('/pesan', [MessageController::class, 'store'])->name('pesan.store');

    // Dashboard khusus panti (hanya untuk role panti)
    Route::middleware('role:panti')->prefix('panti')->group(function () {
        Route::get('/dashboard', function () {
            return view('panti.dashboard');
        })->name('panti.dashboard');
        
        // Tambahkan route khusus panti lainnya di sini
    });

    // Dashboard khusus donatur (hanya untuk role donatur)
    Route::middleware('role:donatur')->prefix('donatur')->group(function () {
        Route::get('/dashboard', function () {
            return view('donatur.dashboard');
        })->name('donatur.dashboard');
    });

    Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Messages
        Route::prefix('messages')->group(function () {
            Route::get('/', [AdminMessageController::class, 'index'])->name('messages.index');
            Route::get('/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
            Route::put('/{message}/reply', [AdminMessageController::class, 'reply'])->name('messages.reply');
        });

        // Users Management
        Route::prefix('users')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('users.index');
            Route::put('/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
            Route::put('/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        });

        // Donatur Management
        Route::prefix('donaturs')->group(function () {
            Route::get('/', [AdminUserController::class, 'getDonatorOnly'])->name('donaturs.index');
            Route::put('/{user}/update-role', [AdminUserController::class, 'updateRole'])->name('donaturs.update-role');
        });

        // Panti Management
        Route::prefix('panti')->group(function () {
            Route::get('/', [AdminPantiController::class, 'index'])->name('panti.index');
            Route::get('/create', [AdminPantiController::class, 'create'])->name('panti.create');
            Route::post('/', [AdminPantiController::class, 'store'])->name('panti.store');
            Route::get('/{panti}', [AdminPantiController::class, 'show'])->name('panti.show');
            Route::get('/{panti}/edit', [AdminPantiController::class, 'edit'])->name('panti.edit');
            Route::put('/{panti}', [AdminPantiController::class, 'update'])->name('panti.update');
            Route::delete('/{panti}', [AdminPantiController::class, 'destroy'])->name('panti.destroy');
        });

        // Admin Transactions
        Route::prefix('transactions')->group(function () {
            Route::get('/', [AdminTransactionController::class, 'index'])->name('transactions.index');
            Route::get('/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
            Route::delete('/{transaction}', [AdminTransactionController::class, 'destroy'])->name('transactions.destroy');
        });
    });

    // Development routes
    Route::get('/riwayat-donasi-dev', function () {
        return view('donasi.riwayat');
    })->name('donasi.riwayat');

    Route::get('/panti/donasi-diterima-dev', function () {
        return view('panti.donasi-diterima');
    })->name('panti.donasi.diterima');

    // halaman riwayat transaksi user yang sedang login
    // tambahin fungsi buat ini di TransaksiController jal
    // halaman riwayat transaksi yang telah diterima panti

});

require __DIR__ . '/auth.php';
