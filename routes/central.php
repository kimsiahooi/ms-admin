<?php

use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::domain($domain)->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome');
    })->name('home');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::resource('tenants', TenantController::class);
    });

    require __DIR__ . '/settings.php';
    require __DIR__ . '/auth.php';
});
