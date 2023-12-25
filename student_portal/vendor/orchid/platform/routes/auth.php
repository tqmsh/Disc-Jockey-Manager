<?php

declare(strict_types=1);

use Orchid\Platform\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Auth web routes
if (config('platform.auth', true)) {
    // Authentication Routes...
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::middleware('throttle:60,1')
        ->post('login', [LoginController::class, 'login'])
        ->name('login.auth');

    Route::post('create', [LoginController::class, 'register'])->name('register');

    Route::get('lock', [LoginController::class, 'resetCookieLockMe'])->name('login.lock');

    //show create account form
    Route::get('register', [LoginController::class, 'showRegisterForm'])->name('register');

}

Route::get('switch-logout', [LoginController::class, 'switchLogout']);
Route::post('switch-logout', [LoginController::class, 'switchLogout'])->name('switch.logout');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
