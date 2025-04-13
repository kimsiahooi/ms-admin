<?php

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

        Route::resource('roles', RoleController::class)->except(['show']);
        Route::prefix('roles')
            ->name('roles.')
            ->group(function () {
                Route::get('audits', [UserController::class, 'audits'])->name('audits');
            });
        Route::match(['put', 'patch'], 'roles/{role}/permissions/update', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');
        Route::resource('users', UserController::class)->except(['show']);
        Route::prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('trashed', [UserController::class, 'trashed'])->name('trashed');
                Route::post('{user}/restore', [UserController::class, 'restore'])->name('restore');
                Route::delete('{user}/force-delete', [UserController::class, 'forceDelete'])->name('forceDelete');
                Route::get('audits', [UserController::class, 'audits'])->name('audits');
            });
        Route::resource('tenants', TenantController::class);
    });

    require __DIR__ . '/settings.php';
    require __DIR__ . '/auth.php';
});
