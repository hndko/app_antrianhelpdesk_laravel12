<?php

use App\Livewire\PublicDisplay;
use App\Livewire\Dashboard;
use App\Livewire\DisplaySettings;
use App\Livewire\ProfileManager;
use App\Livewire\QueueManager;
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
    Route::get('/profile', ProfileManager::class)->name('profile.edit');
    Route::get('/queues', QueueManager::class)->name('queues.index');
    Route::get('/display-settings', DisplaySettings::class)->name('display-settings.index');
    Route::get('/accounts', function () {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        abort_unless($user?->canManageUsers(), 403);

        return view('backend.accounts.index');
    })->name('accounts.index');
    Route::get('/reports/daily', function () {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        abort_unless($user?->canViewReports(), 403);

        return view('backend.reports.daily');
    })->name('reports.daily');
});
