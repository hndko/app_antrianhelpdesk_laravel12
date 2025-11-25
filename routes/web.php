<?php

use App\Livewire\PublicDisplay;
use App\Livewire\AdminDashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Route Public (Tampilan TV)
Route::get('/', PublicDisplay::class)->name('home');

// Route Auth
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Route Admin (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/technicians', function () {
        return view('admin.technicians.index');
    })->name('admin.technicians.index');
    Route::get('/admin/reports/daily', function () {
        return view('admin.reports.daily');
    })->name('admin.reports.daily');
});
