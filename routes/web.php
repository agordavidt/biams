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
use App\Http\Controllers\Analytics\AnalyticsController;


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
        Route::get('/{farmer}/credentials', [FarmerReviewController::class, 'viewCredentials'])->name('view-credentials');
        // Bulk actions
       
        Route::get('/export', [FarmerReviewController::class, 'export'])->name('export');
    });
});






/*
|--------------------------------------------------------------------------
| Enrollment Agent Routes - Submission/Resubmission Workflow
|--------------------------------------------------------------------------
*/


// Enrollment Agent Routes
Route::middleware(['auth', 'role:Enrollment Agent'])->prefix('enrollment')->name('enrollment.')->group(function () {
    Route::get('/dashboard', [EnrollmentDashboardController::class, 'index'])->name('dashboard');
    
    // Farmer enrollment routes
    Route::prefix('farmers')->name('farmers.')->group(function () {
        Route::get('/', [EnrollmentFarmerController::class, 'index'])->name('index'); 
        Route::get('/create', [EnrollmentFarmerController::class, 'create'])->name('create');
        Route::post('/', [EnrollmentFarmerController::class, 'store'])->name('store');
        
        // Show and credentials
        Route::get('/{farmer}', [EnrollmentFarmerController::class, 'show'])->name('show');
        Route::get('/{farmer}/credentials', [EnrollmentFarmerController::class, 'showCredentials'])->name('credentials');
        
        // Edit and update
        Route::get('/{farmer}/edit', [EnrollmentFarmerController::class, 'edit'])->name('edit');
        Route::put('/{farmer}', [EnrollmentFarmerController::class, 'update'])->name('update');
        
        // Farmland management
        Route::get('/{farmer}/farmlands/create', [EnrollmentFarmerController::class, 'createFarmLand'])->name('farmlands.create');
        Route::post('/{farmer}/farmlands', [EnrollmentFarmerController::class, 'storeFarmLand'])->name('farmlands.store');
        
        // Delete
        Route::delete('/{farmer}', [EnrollmentFarmerController::class, 'destroy'])->name('destroy');
    });
});


// Farmer Routes (Standard Users with Farmer Profile)
Route::middleware(['auth', 'role:User'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Marketplace routes
    Route::get('/marketplace', function() {
        return view('user.marketplace');
    })->name('marketplace');
    
    Route::get('/resources', function() {
        return view('user.resources');
    })->name('resources');
});




// Analytics Routes - Role-based access
Route::middleware(['auth'])->prefix('analytics')->name('analytics.')->group(function () {
    
    // Dashboard - All authenticated users with analytics permission
    Route::get('/dashboard', [AnalyticsController::class, 'index'])
        ->name('dashboard')
        ->middleware('can:view_analytics');
    
    // Demographics
    Route::get('/demographics', [AnalyticsController::class, 'demographics'])
        ->name('demographics')
        ->middleware('can:view_analytics');
    
    // Production Analytics
    Route::get('/production', [AnalyticsController::class, 'production'])
        ->name('production')
        ->middleware('can:view_analytics');
    
    // Crop Analytics
    Route::get('/crops', [AnalyticsController::class, 'crops'])
        ->name('crops')
        ->middleware('can:view_analytics');
    
    // Livestock Analytics
    Route::get('/livestock', [AnalyticsController::class, 'livestock'])
        ->name('livestock')
        ->middleware('can:view_analytics');
    
    // Cooperative Analytics
    Route::get('/cooperatives', [AnalyticsController::class, 'cooperatives'])
        ->name('cooperatives')
        ->middleware('can:view_analytics');
    
    // Enrollment Pipeline
    Route::get('/enrollment', [AnalyticsController::class, 'enrollment'])
        ->name('enrollment')
        ->middleware('can:view_analytics');
    
    // Trends (for charts)
    Route::get('/trends', [AnalyticsController::class, 'trends'])
        ->name('trends')
        ->middleware('can:view_analytics');
    
    // LGA Comparison (State-level only)
    Route::get('/lga-comparison', [AnalyticsController::class, 'lgaComparison'])
        ->name('lga_comparison')
        ->middleware('role:Super Admin|Governor|State Admin');
    
    // Agent Performance (LGA Admin only)
    Route::get('/agent-performance', [AnalyticsController::class, 'agentPerformance'])
        ->name('agent_performance')
        ->middleware('role:LGA Admin');
    
    // Export
    Route::get('/export', [AnalyticsController::class, 'export'])
        ->name('export')
        ->middleware('can:export_analytics');
    
    // Manual regeneration (Admin only)
    Route::post('/regenerate', [AnalyticsController::class, 'regenerate'])
        ->name('regenerate')
        ->middleware('role:Super Admin|State Admin');
});





Route::middleware(['auth', 'can:view_analytics'])->prefix('analytics')->name('analytics.')->group(function () {
    
    // Advanced Analytics Routes
    Route::prefix('advanced')->name('advanced.')->group(function () {
        // Main filter interface
        Route::get('/', [AdvancedAnalyticsController::class, 'index'])->name('index');
        
        // Generate custom filtered report
        Route::post('/generate', [AdvancedAnalyticsController::class, 'generate'])->name('generate');
        Route::get('/generate', [AdvancedAnalyticsController::class, 'generate'])->name('generate.get');
        
        // Export filtered results
        Route::get('/export', [AdvancedAnalyticsController::class, 'export'])
            ->name('export')
            ->middleware('can:export_analytics');
        
        // Predefined reports
        Route::get('/predefined', [AdvancedAnalyticsController::class, 'predefinedReports'])->name('predefined');
        Route::get('/predefined/{reportKey}', [AdvancedAnalyticsController::class, 'runPredefinedReport'])->name('predefined.run');
        
        // Comparative analysis
        Route::post('/comparative', [AdvancedAnalyticsController::class, 'comparative'])->name('comparative');
    });
});