<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\ManagementController;
use App\Http\Controllers\Governor\DashboardController as GovernorDashboardController;
use App\Http\Controllers\Governor\PolicyInsightController;
use App\Http\Controllers\Governor\InterventionTrackingController;
use App\Http\Controllers\Governor\LgaComparisonController;
use App\Http\Controllers\Governor\TrendAnalysisController;
use App\Http\Controllers\Admin\DashboardController as StateAdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FarmPracticeController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\ResourceApplicationController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\LGAAdmin\DashboardController as LGAAdminDashboardController;
use App\Http\Controllers\LGAAdmin\ManagementController as LGAAdminManagementController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

use App\Http\Controllers\EnrollmentAgent;

use App\Http\Controllers\EnrollmentAgent\DashboardController as EnrollmentDashboardController;
use App\Http\Controllers\EnrollmentAgent\FarmerController as EnrollmentFarmerController;
use App\Http\Controllers\LGAAdmin\FarmerReviewController;
use App\Http\Controllers\Auth\PasswordController; // Assuming this handles forced change
use App\Http\Controllers\Analytics\AnalyticsController;
use App\Http\Controllers\Analytics\AdvancedAnalyticsController;

use App\Http\Controllers\Support\ChatController;
use App\Http\Controllers\Marketplace\MarketplaceController;
use App\Http\Controllers\Admin\MarketplaceAdminController;

use App\Http\Controllers\LGAAdmin\CooperativeController;
use App\Http\Controllers\Governor\CooperativeOverviewController;
use App\Http\Controllers\Admin\CooperativeViewController;
use App\Http\Controllers\Commissioner\DashboardController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\DistributionDashboardController;




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


Route::get('/about', function () {
    return view('about_us');
})->name('about');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

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

// Add to web.php after the existing Governor routes

// Governor Routes - Extended Analytics
Route::middleware(['auth', 'role:Governor'])->prefix('governor')->name('governor.')->group(function () {
    // Main Dashboard (already exists)
    Route::get('/dashboard', [GovernorDashboardController::class, 'index'])->name('dashboard');
    
    // Policy Insights
    Route::prefix('policy-insights')->name('policy_insights.')->group(function () {
        Route::get('/', [PolicyInsightController::class, 'index'])->name('index');
        Route::get('/demographic-analysis', [PolicyInsightController::class, 'getDemographicAnalysis'])->name('demographic_analysis');
        Route::get('/youth-engagement', [PolicyInsightController::class, 'getYouthEngagement'])->name('youth_engagement');
        Route::get('/yield-projections', [PolicyInsightController::class, 'getYieldProjections'])->name('yield_projections');
        Route::get('/production-patterns', [PolicyInsightController::class, 'getProductionPatterns'])->name('production_patterns');
    });
    
    // Intervention Tracking
    Route::prefix('interventions')->name('interventions.')->group(function () {
        Route::get('/', [InterventionTrackingController::class, 'index'])->name('index');
        Route::get('/beneficiary-report', [InterventionTrackingController::class, 'getBeneficiaryReport'])->name('beneficiary_report');
        Route::get('/partner-activities', [InterventionTrackingController::class, 'getPartnerActivities'])->name('partner_activities');
        Route::get('/coverage-analysis', [InterventionTrackingController::class, 'getCoverageAnalysis'])->name('coverage_analysis');
    });
    
    // LGA Comparison
    Route::prefix('lga-comparison')->name('lga_comparison.')->group(function () {
        Route::get('/', [LgaComparisonController::class, 'index'])->name('index');
        Route::get('/performance-ranking', [LgaComparisonController::class, 'getPerformanceRanking'])->name('performance_ranking');
        Route::get('/capacity-analysis', [LgaComparisonController::class, 'getCapacityAnalysis'])->name('capacity_analysis');
        Route::post('/compare', [LgaComparisonController::class, 'compareLgas'])->name('compare');
        Route::get('/geographic-analysis', [LgaComparisonController::class, 'getGeographicAnalysis'])->name('geographic_analysis');
    });
    
    // Trend Analysis
    Route::prefix('trends')->name('trends.')->group(function () {
        Route::get('/', [TrendAnalysisController::class, 'index'])->name('index');
        Route::get('/enrollment', [TrendAnalysisController::class, 'getEnrollmentTrends'])->name('enrollment');
        Route::get('/production', [TrendAnalysisController::class, 'getProductionTrends'])->name('production');
        Route::get('/resource-utilization', [TrendAnalysisController::class, 'getResourceUtilizationTrends'])->name('resource_utilization');
        Route::get('/gender-parity', [TrendAnalysisController::class, 'getGenderParityTrends'])->name('gender_parity');
    });
});


// State Admin Routes
Route::middleware(['auth', 'role:State Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [StateAdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Users Module
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    
    // Farm Practices Overview
    Route::get('/farm-practices', [FarmPracticeController::class, 'index'])
        ->name('admin.farm-practices.index');
    Route::get('/farm-practices/crops', [FarmPracticeController::class, 'crops'])
        ->name('admin.farm-practices.crops');
    Route::get('/farm-practices/livestock', [FarmPracticeController::class, 'livestock'])
        ->name('admin.farm-practices.livestock');
    Route::get('/farm-practices/fisheries', [FarmPracticeController::class, 'fisheries'])
        ->name('admin.farm-practices.fisheries');
    Route::get('/farm-practices/orchards', [FarmPracticeController::class, 'orchards'])
        ->name('admin.farm-practices.orchards');

    // Partner Management Routes - FIXED: Removed double 'admin/' prefix
    Route::get('/partners', [PartnerController::class, 'index'])->name('admin.partners.index');
    Route::get('/partners/create', [PartnerController::class, 'create'])->name('admin.partners.create');
    Route::post('/partners', [PartnerController::class, 'store'])->name('admin.partners.store');
    Route::get('/partners/{partner}', [PartnerController::class, 'show'])->name('admin.partners.show');
    Route::get('/partners/{partner}/edit', [PartnerController::class, 'edit'])->name('admin.partners.edit');
    Route::put('/partners/{partner}', [PartnerController::class, 'update'])->name('admin.partners.update');
    Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])->name('admin.partners.destroy');

    // Vendor Management Routes - UPDATED WITH ALL ROUTES
    Route::get('/vendors', [\App\Http\Controllers\Admin\VendorController::class, 'index'])->name('admin.vendors.index');
    Route::get('/vendors/create', [\App\Http\Controllers\Admin\VendorController::class, 'create'])->name('admin.vendors.create');
    Route::post('/vendors', [\App\Http\Controllers\Admin\VendorController::class, 'store'])->name('admin.vendors.store');
    Route::get('/vendors/{vendor}', [\App\Http\Controllers\Admin\VendorController::class, 'show'])->name('admin.vendors.show');
    Route::get('/vendors/{vendor}/edit', [\App\Http\Controllers\Admin\VendorController::class, 'edit'])->name('admin.vendors.edit');
    Route::put('/vendors/{vendor}', [\App\Http\Controllers\Admin\VendorController::class, 'update'])->name('admin.vendors.update');
    Route::delete('/vendors/{vendor}', [\App\Http\Controllers\Admin\VendorController::class, 'destroy'])->name('admin.vendors.destroy');
    Route::patch('/vendors/{vendor}/toggle-status', [\App\Http\Controllers\Admin\VendorController::class, 'toggleStatus'])->name('admin.vendors.toggle-status');

    // Resource Routes - FIXED: Removed double 'admin/' prefix
    Route::get('/resources', [\App\Http\Controllers\Admin\ResourceController::class, 'index'])->name('admin.resources.index');
    Route::get('/resources/create', [\App\Http\Controllers\Admin\ResourceController::class, 'create'])->name('admin.resources.create');
    Route::post('/resources', [\App\Http\Controllers\Admin\ResourceController::class, 'store'])->name('admin.resources.store');
    Route::get('/resources/{resource}/edit', [\App\Http\Controllers\Admin\ResourceController::class, 'edit'])->name('admin.resources.edit');
    Route::put('/resources/{resource}', [\App\Http\Controllers\Admin\ResourceController::class, 'update'])->name('admin.resources.update');
    Route::delete('/resources/{resource}', [\App\Http\Controllers\Admin\ResourceController::class, 'destroy'])->name('admin.resources.destroy');
    
    // Resource Application Management Routes - FIXED: Removed redundant 'resources/' prefix
    Route::get('/applications', [ResourceApplicationController::class, 'index'])->name('resources.applications.index');
    Route::get('/applications/{application}', [ResourceApplicationController::class, 'show'])->name('resources.applications.show');
    Route::post('/applications/{application}/grant', [ResourceApplicationController::class, 'grant'])->name('resources.applications.grant');
    Route::post('/applications/{application}/decline', [ResourceApplicationController::class, 'decline'])->name('resources.applications.decline');
    Route::post('/applications/bulk-update', [ResourceApplicationController::class, 'bulkUpdate'])->name('resources.applications.bulk-update');
    Route::get('/applications/export', [ResourceApplicationController::class, 'export'])->name('resources.applications.export');
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
    
     // Resources routes - for viewing and applying
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::get('/', [\App\Http\Controllers\User\ResourceController::class, 'index'])->name('index');
        Route::get('/{resource}', [\App\Http\Controllers\User\ResourceController::class, 'show'])->name('show');
        Route::get('/{resource}/apply', [\App\Http\Controllers\User\ResourceController::class, 'apply'])->name('apply');
        Route::post('/{resource}/submit', [\App\Http\Controllers\User\ResourceController::class, 'submit'])->name('submit');
        
        // Payment handling
        Route::post('/{resource}/payment/initiate', [\App\Http\Controllers\User\ResourceController::class, 'initiatePayment'])->name('payment.initiate');
        
        // Track applications - MUST come before /{application} to avoid route conflict
        Route::get('/applications/track', [\App\Http\Controllers\User\ResourceController::class, 'track'])->name('track');
        Route::get('/applications/{application}', [\App\Http\Controllers\User\ResourceController::class, 'showApplication'])->name('applications.show');
    });
    
    // Payment callback (outside resources prefix to match Credo callback URL)
    Route::get('payment/callback', [\App\Http\Controllers\User\ResourceController::class, 'handlePaymentCallback'])->name('payment.callback');
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



/*
|--------------------------------------------------------------------------
| Farmer Support Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:User'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/', [ChatController::class, 'store'])->name('store');
        Route::get('/{chat}', [ChatController::class, 'show'])->name('show');
        Route::post('/{chat}/messages', [ChatController::class, 'sendMessage'])->name('send-message');
        Route::get('/{chat}/poll', [ChatController::class, 'poll'])->name('poll');
    });
});

/*
|--------------------------------------------------------------------------
| LGA Admin Support Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'permission:view_lga_dashboard'])->prefix('lga-admin')->name('lga_admin.')->group(function () {
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{chat}', [ChatController::class, 'show'])->name('show');
        Route::post('/{chat}/messages', [ChatController::class, 'sendMessage'])->name('send-message');
        Route::post('/{chat}/assign', [ChatController::class, 'assign'])->name('assign');
        Route::post('/{chat}/resolve', [ChatController::class, 'resolve'])->name('resolve');
        Route::get('/{chat}/poll', [ChatController::class, 'poll'])->name('poll');
    });
});

/*
|--------------------------------------------------------------------------
| State Admin Support Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:State Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{chat}', [ChatController::class, 'show'])->name('show');
        Route::post('/{chat}/messages', [ChatController::class, 'sendMessage'])->name('send-message');
        Route::post('/{chat}/assign', [ChatController::class, 'assign'])->name('assign');
        Route::post('/{chat}/resolve', [ChatController::class, 'resolve'])->name('resolve');
        Route::get('/{chat}/poll', [ChatController::class, 'poll'])->name('poll');
    });
});




/*
|--------------------------------------------------------------------------
| Marketplace Routes - Public & Authenticated Access
|--------------------------------------------------------------------------
*/



// Public Marketplace Routes (No Authentication Required)
Route::prefix('marketplace')->name('marketplace.')->group(function () {
    // Browse listings (public)
    Route::get('/', [MarketplaceController::class, 'index'])->name('index');
    
    // View single listing details (public)
    Route::get('/listings/{listing}', [MarketplaceController::class, 'show'])->name('show');
    
    // Contact farmer (lead generation - no auth required)
    Route::post('/listings/{listing}/contact', [MarketplaceController::class, 'contactFarmer'])->name('contact-farmer');
});



// Farmer Marketplace Routes
Route::middleware(['auth', 'role:User'])->prefix('farmer/marketplace')->name('farmer.marketplace.')->group(function () {
    // My Listings Dashboard
    Route::get('/my-listings', [MarketplaceController::class, 'myListings'])->name('my-listings');
    
    // Subscription & Payment
    Route::post('/subscribe', [MarketplaceController::class, 'initiatePayment'])->name('subscribe');
    Route::get('/payment/callback', [MarketplaceController::class, 'handlePaymentCallback'])->name('payment.callback');
    
    // Listing Management (Requires Active Subscription)
    Route::middleware('can:create,App\Models\Market\MarketplaceListing')->group(function () {
        Route::get('/create', [MarketplaceController::class, 'create'])->name('create');
        Route::post('/listings', [MarketplaceController::class, 'store'])->name('store');
        Route::get('/listings/{listing}/edit', [MarketplaceController::class, 'edit'])->name('edit');
        Route::put('/listings/{listing}', [MarketplaceController::class, 'update'])->name('update');
        Route::delete('/listings/{listing}', [MarketplaceController::class, 'destroy'])->name('destroy');
    });
    
    // View leads/inquiries received
    Route::get('/leads', [MarketplaceController::class, 'myLeads'])->name('leads');
});

// State Admin Marketplace Management Routes
Route::middleware(['auth', 'role:State Admin', 'permission:manage_supplier_catalog'])
    ->prefix('admin/marketplace')
    ->name('admin.marketplace.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [MarketplaceAdminController::class, 'dashboard'])->name('dashboard');
        
        // Listings Management
        Route::get('/listings', [MarketplaceAdminController::class, 'listings'])->name('listings');
        Route::post('/listings/{listing}/approve', [MarketplaceAdminController::class, 'approveListing'])->name('approve');
        Route::post('/listings/{listing}/reject', [MarketplaceAdminController::class, 'rejectListing'])->name('reject');
        Route::delete('/listings/{listing}', [MarketplaceAdminController::class, 'removeListing'])->name('remove');
        
        // Category Management
        Route::get('/categories', [MarketplaceAdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [MarketplaceAdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [MarketplaceAdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [MarketplaceAdminController::class, 'deleteCategory'])->name('categories.destroy');
        
        // Subscription Management
        Route::get('/subscriptions', [MarketplaceAdminController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/subscriptions/export', [MarketplaceAdminController::class, 'exportSubscriptions'])->name('subscriptions.export');
        
        // Analytics & Reports
        Route::get('/analytics', [MarketplaceAdminController::class, 'analytics'])->name('analytics');
        Route::get('/reports/export', [MarketplaceAdminController::class, 'exportReport'])->name('reports.export');
    });







// ============================================================================
// STATE ADMIN COOPERATIVE ROUTES - View Only (No CRUD)
// ============================================================================
Route::middleware(['auth', 'role:State Admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Cooperative viewing routes
    Route::prefix('cooperatives')->name('cooperatives.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\CooperativeViewController::class, 'index'])
            ->name('index')
            ->middleware('permission:view_all_cooperatives');
        
        Route::get('/{cooperative}', [App\Http\Controllers\Admin\CooperativeViewController::class, 'show'])
            ->name('show')
            ->middleware('permission:view_cooperative_details');
        
        Route::get('/export', [App\Http\Controllers\Admin\CooperativeViewController::class, 'export'])
            ->name('export')
            ->middleware('permission:export_cooperatives');
    });
});

// ============================================================================
// GOVERNOR COOPERATIVE ROUTES - Overview & Statistics Only
// ============================================================================
Route::middleware(['auth', 'role:Governor'])->prefix('governor')->name('governor.')->group(function () {
    
    // Cooperative overview and analytics
    Route::prefix('cooperatives')->name('cooperatives.')->group(function () {
        Route::get('/overview', [App\Http\Controllers\Governor\CooperativeOverviewController::class, 'index'])
            ->name('overview')
            ->middleware('permission:view_cooperative_overview');
        
        Route::get('/lga-comparison', [App\Http\Controllers\Governor\CooperativeOverviewController::class, 'lgaComparison'])
            ->name('lga_comparison')
            ->middleware('permission:view_cooperative_overview');
        
        Route::get('/export-overview', [App\Http\Controllers\Governor\CooperativeOverviewController::class, 'exportOverview'])
            ->name('export_overview')
            ->middleware('permission:view_cooperative_overview');
    });
});

// ============================================================================
// LGA ADMIN COOPERATIVE ROUTES - Full CRUD within LGA
// ============================================================================
Route::middleware(['auth', 'permission:view_lga_dashboard'])->prefix('lga-admin')->name('lga_admin.')->group(function () {
    
    // COOPERATIVE MANAGEMENT ROUTES
    Route::middleware('permission:manage_lga_cooperatives')->prefix('cooperatives')->name('cooperatives.')->group(function () {
        // List and create
        Route::get('/', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'store'])->name('store');
        
        // View, edit, delete
        Route::get('/{cooperative}', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'show'])->name('show');
        Route::get('/{cooperative}/edit', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'edit'])->name('edit');
        Route::put('/{cooperative}', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'update'])->name('update');
        Route::delete('/{cooperative}', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'destroy'])->name('destroy');
        
        // Member management
        Route::get('/{cooperative}/members', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'members'])->name('members');
        Route::post('/{cooperative}/members/add', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'addMember'])->name('members.add');
        Route::delete('/{cooperative}/members/{farmer}', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'removeMember'])->name('members.remove');
        
        // Export
        Route::get('/export/excel', [App\Http\Controllers\LGAAdmin\CooperativeController::class, 'export'])->name('export');
    });
});






// =====================================================
// Commissioner Routes (Add to routes/web.php)
// =====================================================

Route::middleware(['auth', 'role:Commissioner'])->prefix('commissioner')->name('commissioner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Commissioner\DashboardController::class, 'index'])
        ->name('dashboard');
    
    // Policy Insights
    Route::prefix('policy-insights')->name('policy_insights.')->group(function () {
        Route::get('/', [App\Http\Controllers\Commissioner\PolicyInsightController::class, 'index'])
            ->name('index');
        Route::get('/demographic-analysis', [App\Http\Controllers\Commissioner\PolicyInsightController::class, 'getDemographicAnalysis'])
            ->name('demographic_analysis');
        Route::get('/youth-engagement', [App\Http\Controllers\Commissioner\PolicyInsightController::class, 'getYouthEngagement'])
            ->name('youth_engagement');
        Route::get('/yield-projections', [App\Http\Controllers\Commissioner\PolicyInsightController::class, 'getYieldProjections'])
            ->name('yield_projections');
        Route::get('/production-patterns', [App\Http\Controllers\Commissioner\PolicyInsightController::class, 'getProductionPatterns'])
            ->name('production_patterns');
    });

    // Trend Analysis
    Route::prefix('trends')->name('trends.')->group(function () {
        Route::get('/', [App\Http\Controllers\Commissioner\TrendAnalysisController::class, 'index'])
            ->name('index');
        Route::get('/enrollment-trends', [App\Http\Controllers\Commissioner\TrendAnalysisController::class, 'getEnrollmentTrends'])
            ->name('enrollment_trends');
        Route::get('/production-trends', [App\Http\Controllers\Commissioner\TrendAnalysisController::class, 'getProductionTrends'])
            ->name('production_trends');
        Route::get('/resource-utilization-trends', [App\Http\Controllers\Commissioner\TrendAnalysisController::class, 'getResourceUtilizationTrends'])
            ->name('resource_utilization_trends');
        Route::get('/gender-parity-trends', [App\Http\Controllers\Commissioner\TrendAnalysisController::class, 'getGenderParityTrends'])
            ->name('gender_parity_trends');
    });

    // LGA Comparison
    Route::prefix('lga-comparison')->name('lga_comparison.')->group(function () {
        Route::get('/', [App\Http\Controllers\Commissioner\LgaComparisonController::class, 'index'])
            ->name('index');
        Route::get('/performance-ranking', [App\Http\Controllers\Commissioner\LgaComparisonController::class, 'getPerformanceRanking'])
            ->name('performance_ranking');
        Route::get('/capacity-analysis', [App\Http\Controllers\Commissioner\LgaComparisonController::class, 'getCapacityAnalysis'])
            ->name('capacity_analysis');
        Route::get('/compare-lgas', [App\Http\Controllers\Commissioner\LgaComparisonController::class, 'compareLgas'])
            ->name('compare_lgas');
        Route::get('/geographic-analysis', [App\Http\Controllers\Commissioner\LgaComparisonController::class, 'getGeographicAnalysis'])
            ->name('geographic_analysis');
    });

    // Intervention Tracking
    Route::prefix('interventions')->name('interventions.')->group(function () {
        Route::get('/', [App\Http\Controllers\Commissioner\InterventionTrackingController::class, 'index'])
            ->name('index');
        Route::get('/beneficiary-report', [App\Http\Controllers\Commissioner\InterventionTrackingController::class, 'getBeneficiaryReport'])
            ->name('beneficiary_report');
        Route::get('/partner-activities', [App\Http\Controllers\Commissioner\InterventionTrackingController::class, 'getPartnerActivities'])
            ->name('partner_activities');
        Route::get('/coverage-analysis', [App\Http\Controllers\Commissioner\InterventionTrackingController::class, 'getCoverageAnalysis'])
            ->name('coverage_analysis');
    });
});



// NEW: Vendor Manager Dashboard
Route::middleware(['auth', 'role:Vendor Manager'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
});

// NEW: Distribution Agent Dashboard
Route::middleware(['auth', 'role:Distribution Agent'])->prefix('vendor/distribution')->name('vendor.distribution.')->group(function () {
    Route::get('/dashboard', [DistributionDashboardController::class, 'index'])->name('dashboard');
});