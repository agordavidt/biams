<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\ManagementController;
use App\Http\Controllers\Governor\DashboardController as GovernorDashboardController;
use App\Http\Controllers\Admin\DashboardController as StateAdminDashboardController;
use App\Http\Controllers\LGAAdmin\DashboardController as LGAAdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Base Level)
|--------------------------------------------------------------------------
*/
// Note: Removed 'onboarded' middleware check here if it was custom, as the status logic is removed
Route::middleware(['auth'])->group(function () {
    Route::get(RouteServiceProvider::HOME, [UserDashboardController::class, 'index'])->name('home');
    Route::get('/marketplace', function() {
        return view('user.marketplace');
    })->name('marketplace')->middleware('role:User');
});

/*
|--------------------------------------------------------------------------
| Role-Gated Admin Routes (using Spatie Role Middleware)
|--------------------------------------------------------------------------
*/

// Super Admin Routes
Route::middleware(['auth', 'role:Super Admin', 'permission:manage_users'])->prefix('super-admin')->name('super_admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Management Routes (Simplified example, ensure permissions are applied across all routes)
    Route::prefix('management')->name('management.')->group(function () {
        // Management Index
        Route::get('/', [ManagementController::class, 'index'])->name('index');
        
        // User Management Routes
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [ManagementController::class, 'users'])->name('index');
            Route::get('/create', [ManagementController::class, 'createUser'])->name('create');
            Route::post('/', [ManagementController::class, 'storeUser'])->name('store');
            Route::get('/{user}/edit', [ManagementController::class, 'editUser'])->name('edit');
            Route::put('/{user}', [ManagementController::class, 'updateUser'])->name('update');
            Route::delete('/{user}', [ManagementController::class, 'destroyUser'])->name('destroy');
        });

        // Department Management Routes
        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', [ManagementController::class, 'departments'])->name('index');
            Route::post('/', [ManagementController::class, 'storeDepartment'])->name('store');
            Route::put('/{department}', [ManagementController::class, 'updateDepartment'])->name('update');
            Route::delete('/{department}', [ManagementController::class, 'destroyDepartment'])->name('destroy');
        });

        // Agency Management Routes
        Route::prefix('agencies')->name('agencies.')->group(function () {
            Route::get('/', [ManagementController::class, 'agencies'])->name('index');
            Route::post('/', [ManagementController::class, 'storeAgency'])->name('store');
            Route::put('/{agency}', [ManagementController::class, 'updateAgency'])->name('update');
            Route::delete('/{agency}', [ManagementController::class, 'destroyAgency'])->name('destroy');
        });

        // LGA Management Routes
        Route::prefix('lgas')->name('lgas.')->group(function () {
            Route::get('/', [ManagementController::class, 'lgas'])->name('index');
        });
    });
});

// Governor Routes
Route::middleware(['auth', 'role:Governor'])->prefix('governor')->group(function () {
    Route::get('/dashboard', [GovernorDashboardController::class, 'index'])->name('governor.dashboard');
});

// State Admin Routes
Route::middleware(['auth', 'role:State Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [StateAdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// LGA Admin Routes
// 💡 FIX: Using permission middleware is often more reliable for granular dashboard access.
// Ensure the LGA Admin role has the 'view_lga_dashboard' permission in the database.
Route::middleware(['auth', 'permission:view_lga_dashboard'])->prefix('lga-admin')->group(function () {
    Route::get('/dashboard', [LGAAdminDashboardController::class, 'index'])->name('lga_admin.dashboard');
});
