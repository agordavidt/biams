<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\ManagementController;
use App\Http\Controllers\Governor\DashboardController as GovernorDashboardController;
use App\Http\Controllers\Admin\DashboardController as StateAdminDashboardController;
use App\Http\Controllers\LGAAdmin\DashboardController as LGAAdminDashboardController;
use App\Http\Controllers\LGAAdmin\ManagementController as LGAAdminManagementController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\EnrollmentAgent;


// NEW IMPORTS for Phase 3 Controllers
use App\Http\Controllers\EnrollmentAgent\DashboardController as EnrollmentDashboardController;
use App\Http\Controllers\EnrollmentAgent\FarmerController as EnrollmentFarmerController;
use App\Http\Controllers\LGAAdmin\FarmerReviewController;
use App\Http\Controllers\Auth\PasswordController; // Assuming this handles forced change


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
Route::middleware(['auth'])->group(function () {    
    Route::get('/password/force-change', [PasswordController::class, 'forceChangePasswordForm'])
         ->name('password.force_change');
    Route::post('/password/update-initial', [PasswordController::class, 'updateInitialPassword'])
         ->name('password.update_initial');
         
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
Route::middleware(['auth', 'permission:view_lga_dashboard'])->prefix('lga-admin')->name('lga_admin.')->group(function () {
    Route::get('/dashboard', [LGAAdminDashboardController::class, 'index'])->name('dashboard');

    // Management Routes for Enrollment Agents
    Route::middleware('permission:manage_lga_agents')->prefix('agents')->name('agents.')->group(function () {
        Route::get('/', [LGAAdminManagementController::class, 'index'])->name('index');
        Route::get('/create', [LGAAdminManagementController::class, 'create'])->name('create');
        Route::post('/', [LGAAdminManagementController::class, 'store'])->name('store');
        Route::get('/{agent}/edit', [LGAAdminManagementController::class, 'edit'])->name('edit');
        Route::put('/{agent}', [LGAAdminManagementController::class, 'update'])->name('update');
        Route::delete('/{agent}', [LGAAdminManagementController::class, 'destroy'])->name('destroy');
    });

     // FARMER ENROLLMENT REVIEW ROUTES
    Route::prefix('farmers')->name('farmers.')->group(function () {
        // List pending submissions and reviewed submissions
        Route::get('/', [FarmerReviewController::class, 'index'])->name('index');
        // View the detailed profile for review
        Route::get('/{farmer}', [FarmerReviewController::class, 'show'])->name('show');
        
        // Action: Approve the enrollment
        Route::post('/{farmer}/approve', [FarmerReviewController::class, 'approve'])->name('approve');
        // Action: Reject the enrollment and provide a reason
        Route::post('/{farmer}/reject', [FarmerReviewController::class, 'reject'])->name('reject');
        // Action: Trigger activation and create User account
        Route::post('/{farmer}/activate', [FarmerReviewController::class, 'activate'])->name('activate');
    });
});






/*
|--------------------------------------------------------------------------
| Enrollment Agent Routes - Submission/Resubmission Workflow
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Enrollment Agent'])->prefix('enrollment')->name('enrollment.')->group(function () {
    Route::get('/dashboard', [EnrollmentDashboardController::class, 'index'])->name('dashboard');
    
    // Farmer enrollment routes
    Route::prefix('farmers')->name('farmers.')->group(function () {
        // Index shows ALL submissions (Pending, Rejected, Accepted/Active)
        Route::get('/', [EnrollmentFarmerController::class, 'index'])->name('index'); 
        Route::get('/create', [EnrollmentFarmerController::class, 'create'])->name('create');
        // Submission route
        Route::post('/', [EnrollmentFarmerController::class, 'store'])->name('store');
        
        // Show route to view details and rejection reason
        Route::get('/{farmer}', [EnrollmentFarmerController::class, 'show'])->name('show');
        
        // The EDIT route is now specifically for UPDATING/RESUBMITTING a PENDING/REJECTED farmer
        Route::get('/{farmer}/edit', [EnrollmentFarmerController::class, 'edit'])->name('edit');
        Route::put('/{farmer}', [EnrollmentFarmerController::class, 'update'])->name('update');
        
        // Route to delete a submission (only if pending/rejected)
        Route::delete('/{farmer}', [EnrollmentFarmerController::class, 'destroy'])->name('destroy');
    });
});
