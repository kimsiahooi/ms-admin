<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Tenant\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Tenant\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Tenant\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Tenant\Auth\NewPasswordController;
use App\Http\Controllers\Tenant\Auth\PasswordResetLinkController;
use App\Http\Controllers\Tenant\Auth\RegisteredUserController;
use App\Http\Controllers\Tenant\Auth\VerifyEmailController;
use App\Http\Controllers\Tenant\BomController;
use App\Http\Controllers\Tenant\CustomerBranchController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\MachineController;
use App\Http\Controllers\Tenant\MaterialController;
use App\Http\Controllers\Tenant\OperationController;
use App\Http\Controllers\Tenant\OperationTenantUserController;
use App\Http\Controllers\Tenant\PlantController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\ProductPresetController;
use App\Http\Controllers\Tenant\ProductPriceController;
use App\Http\Controllers\Tenant\RouteController;
use App\Http\Controllers\Tenant\Settings\PasswordController;
use App\Http\Controllers\Tenant\Settings\ProfileController;
use App\Http\Controllers\Tenant\TaskController;
use App\Http\Controllers\Tenant\TenantUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByPath::class,
    'tenant_status',
])->prefix('{tenant}')->group(function () {
    Route::get('/', function () {
        return to_route('dashboard', ['tenant' => tenant('id')]);
    });

    Route::middleware('guest')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])
            ->name('register');

        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');
    });

    Route::middleware('auth')->group(function () {
        Route::get('verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::get('dashboard', function () {
            return inertia('Tenant/Dashboard');
        })->name('dashboard');

        Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
        Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

        Route::get('settings/appearance', function () {
            return Inertia::render('Tenant/settings/Appearance');
        })->name('appearance');

        Route::resource('users', TenantUserController::class)->except(['create', 'show']);

        Route::prefix('plants/{plant}')->name('plants.')->group(function () {
            Route::match(['put', 'patch'], 'toggleStatus', [PlantController::class, 'toggleStatus'])->name('toggleStatus');

            Route::prefix('operations/{operation}')->name('operations.')->group(function () {
                Route::match(['put', 'patch'], 'toggleStatus', [OperationController::class, 'toggleStatus'])->name('toggleStatus');

                Route::prefix('users/{user}')->name('users.')->group(function () {
                    Route::match(['put', 'patch'], 'toggleStatus', [OperationTenantUserController::class, 'toggleStatus'])->name('toggleStatus');
                });

                Route::prefix('tasks/{task}')->name('tasks.')->group(function () {
                    Route::match(['put', 'patch'], 'toggleStatus', [TaskController::class, 'toggleStatus'])->name('toggleStatus');
                });
            });
        });
        Route::resource('plants', PlantController::class)->except(['create', 'show']);
        Route::resource('plants.operations', OperationController::class)->except(['create', 'show']);
        Route::resource('plants.operations.users', OperationTenantUserController::class)->except(['create', 'show']);
        Route::resource('plants.operations.tasks', TaskController::class)->except(['create', 'show']);

        Route::prefix('machines/{machine}')->name('machines.')->group(function () {
            Route::match(['put', 'patch'], 'toggleStatus', [MachineController::class, 'toggleStatus'])->name('toggleStatus');
        });
        Route::resource('machines', MachineController::class)->except(['create', 'show']);

        Route::prefix('materials/{material}')->name('materials.')->group(function () {
            Route::match(['put', 'patch'], 'toggleStatus', [MaterialController::class, 'toggleStatus'])->name('toggleStatus');
        });
        Route::resource('materials', MaterialController::class)->except(['create', 'show']);

        Route::prefix('products/{product}')->name('products.')->group(function () {
            Route::match(['put', 'patch'], 'toggleStatus', [ProductController::class, 'toggleStatus'])->name('toggleStatus');

            Route::prefix('prices/{price}')->name('prices.')->group(function () {
                Route::match(['put', 'patch'], 'toggleStatus', [ProductPriceController::class, 'toggleStatus'])->name('toggleStatus');
            });

            Route::prefix('presets/{preset}')->name('presets.')->group(function () {
                Route::match(['put', 'patch'], 'toggleStatus', [ProductPresetController::class, 'toggleStatus'])->name('toggleStatus');
            });

            Route::prefix('boms/{bom}')->name('boms.')->group(function () {
                Route::match(['put', 'patch'], 'toggleStatus', [BomController::class, 'toggleStatus'])->name('toggleStatus');
            });
        });
        Route::resource('products', ProductController::class)->except(['create', 'show']);
        Route::resource('products.prices', ProductPriceController::class)->except(['create', 'show']);
        Route::resource('products.presets', ProductPresetController::class)->except(['create', 'show']);
        Route::resource('products.boms', BomController::class)->except(['show']);

        Route::prefix('customers/{customer}')->name('customers.')->group(function () {
            Route::match(['put', 'patch'], 'toggleStatus', [CustomerController::class, 'toggleStatus'])->name('toggleStatus');

            Route::prefix('branches/{branch}')->name('branches.')->group(function () {
                Route::match(['put', 'patch'], 'toggleStatus', [CustomerBranchController::class, 'toggleStatus'])->name('toggleStatus');
            });
        });
        Route::resource('customers', CustomerController::class)->except(['create', 'show']);
        Route::resource('customers.branches', CustomerBranchController::class)->except(['create', 'show']);

        Route::prefix('routes/{route}')->name('routes.')->group(function () {
            Route::match(['put', 'patch'], 'toggleStatus', [RouteController::class, 'toggleStatus'])->name('toggleStatus');
        });
        Route::resource('routes', RouteController::class)->except(['create', 'show']);
    });
});
