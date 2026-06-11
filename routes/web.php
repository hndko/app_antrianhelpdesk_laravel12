<?php

use App\Livewire\PublicDisplay;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Route Public (Tampilan TV)
Route::get('/', PublicDisplay::class)->name('home');

// Route Auth
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate'])->middleware('throttle:5,1');
});

// Route Backend (Protected)
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/technicians', function () {
        return view('backend.technicians.index');
    })->name('technicians.index');
    Route::get('/reports/daily', function () {
        return view('backend.reports.daily');
    })->name('reports.daily');
});
