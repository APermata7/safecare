<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PantiAsuhanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [PantiAsuhanController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/panti-asuhan', [PantiAsuhanController::class, 'index']);
    Route::get('/panti-asuhan/{id}', [PantiAsuhanController::class, 'show'])->name('panti.show');
    Route::post('/donasi', [TransaksiController::class, 'createDonation'])->name('donasi.create');

    // user management for admin only
    Route::prefix('admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin');
        Route::put('/{id}/ban', [UserController::class, 'ban']);
        Route::put('/{id}/unban', [UserController::class, 'unban']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::put('/{id}/role', [UserController::class, 'updateRole']);
    });
});

require __DIR__.'/auth.php';
