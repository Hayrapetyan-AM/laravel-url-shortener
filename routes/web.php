<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/links', [LinkController::class, 'store'])->name('links.store');

Route::get('/dashboard', fn () => redirect()->route('filament.admin.pages.dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{shortCode}', RedirectController::class)
    ->where('shortCode', '[A-Za-z0-9]+')
    ->name('redirect');
