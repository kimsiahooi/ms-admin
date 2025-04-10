<?php

use App\Enums\Roles\UserRolesEnum;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
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

        Route::put('roles/{role}/permissions/update', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('users', UserController::class);
        Route::resource('tenants', TenantController::class);
    });

    require __DIR__ . '/settings.php';
    require __DIR__ . '/auth.php';
});
