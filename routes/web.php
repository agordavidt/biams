<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\ManagementController;
use App\Http\Controllers\Governor\DashboardController as GovernorDashboardController;
use App\Http\Controllers\Governor\PolicyInsightController;
use App\Http\Controllers\Governor\InterventionTrackingController;
use App\Http\Controllers\Governor\LgaComparisonController;
use App\Http\Controllers\Governor\TrendAnalysisController;
use App\Http\Controllers\Governor\CooperativeOverviewController;
use App\Http\Controllers\Admin\DashboardController as StateAdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FarmPracticeController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\ResourceReviewController;
use App\Http\Controllers\Admin\ResourceApplicationController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\CooperativeViewController;
use App\Http\Controllers\Admin\MarketplaceAdminController;
use App\Http\Controllers\LGAAdmin\DashboardController as LGAAdminDashboardController;
use App\Http\Controllers\LGAAdmin\ManagementController as LGAAdminManagementController;
use App\Http\Controllers\LGAAdmin\FarmerReviewController;
use App\Http\Controllers\LGAAdmin\CooperativeController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ResourceController as UserResourceController;
use App\Http\Controllers\User\FarmerResourceController;
use App\Http\Controllers\EnrollmentAgent\DashboardController as EnrollmentDashboardController;
use App\Http\Controllers\EnrollmentAgent\FarmerController as EnrollmentFarmerController;
use App\Http\Controllers\Analytics\AnalyticsController;
use App\Http\Controllers\Analytics\AdvancedAnalyticsController;
use App\Http\Controllers\Support\ChatController;
use App\Http\Controllers\Marketplace\MarketplaceController;
use App\Http\Controllers\Commissioner\DashboardController as CommissionerDashboardController;
use App\Http\Controllers\Commissioner\PolicyInsightController as CommissionerPolicyInsightController;
use App\Http\Controllers\Commissioner\TrendAnalysisController as CommissionerTrendAnalysisController;
use App\Http\Controllers\Commissioner\LgaComparisonController as CommissionerLgaComparisonController;
use App\Http\Controllers\Commissioner\InterventionTrackingController as CommissionerInterventionTrackingController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\DistributionDashboardController;
use App\Http\Controllers\Vendor\ResourceController as VendorResourceController;
use App\Http\Controllers\Vendor\TeamController as VendorTeamController;
use App\Http\Controllers\Vendor\DistributionFulfillmentController;
use App\Http\Controllers\Vendor\AnalyticsController as VendorAnalyticsController;
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
});

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Super Admin', 'permission:manage_users'])->prefix('super-admin')->name('super_admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Management Routes
    Route::prefix('management')->name('management.')->group(function () {
        Route::get('/', [ManagementController::class, 'index'])->name('index');
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [ManagementController::class, 'users'])->name('index');
            Route::get('/create', [ManagementController::class, 'createUser'])->name('create');
            Route::post('/', [ManagementController::class, 'storeUser'])->name('store');
            Route::get('/{user}/edit', [ManagementController::class, 'editUser'])->name('edit');
            Route::put('/{user}', [ManagementController::class, 'updateUser'])->name('update');
            Route::delete('/{user}', [ManagementController::class, 'destroyUser'])->name('destroy');
        });

        // Department Management
        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', [ManagementController::class, 'departments'])->name('index');
            Route::post('/', [ManagementController::class, 'storeDepartment'])->name('store');
            Route::put('/{department}', [ManagementController::class, 'updateDepartment'])->name('update');
            Route::delete('/{department}', [ManagementController::class, 'destroyDepartment'])->name('destroy');
        });

        // Agency Management
        Route::prefix('agencies')->name('agencies.')->group(function () {
            Route::get('/', [ManagementController::class, 'agencies'])->name('index');
            Route::post('/', [ManagementController::class, 'storeAgency'])->name('store');
            Route::put('/{agency}', [ManagementController::class, 'updateAgency'])->name('update');
            Route::delete('/{agency}', [ManagementController::class, 'destroyAgency'])->name('destroy');
        });

        // LGA Management
        Route::prefix('lgas')->name('lgas.')->group(function () {
            Route::get('/', [ManagementController::class, 'lgas'])->name('index');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Governor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Governor'])->prefix('governor')->name('governor.')->group(function () {
    // Dashboard
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

    // Cooperative Overview
    Route::prefix('cooperatives')->name('cooperatives.')->group(function () {
        Route::get('/overview', [CooperativeOverviewController::class, 'index'])
            ->name('overview')
            ->middleware('permission:view_cooperative_overview');
        Route::get('/lga-comparison', [CooperativeOverviewController::class, 'lgaComparison'])
            ->name('lga_comparison')
            ->middleware('permission:view_cooperative_overview');
        Route::get('/export-overview', [CooperativeOverviewController::class, 'exportOverview'])
            ->name('export_overview')
            ->middleware('permission:view_cooperative_overview');
    });
});

/*
|--------------------------------------------------------------------------
| State Admin Routes (UPDATED FOR STREAMLINED WORKFLOW)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:State Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [StateAdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users Module
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    
    // Farm Practices
    Route::prefix('farm-practices')->name('farm-practices.')->group(function () {
        Route::get('/', [FarmPracticeController::class, 'index'])->name('index');
        Route::get('/crops', [FarmPracticeController::class, 'crops'])->name('crops');
        Route::get('/livestock', [FarmPracticeController::class, 'livestock'])->name('livestock');
        Route::get('/fisheries', [FarmPracticeController::class, 'fisheries'])->name('fisheries');
        Route::get('/orchards', [FarmPracticeController::class, 'orchards'])->name('orchards');
    });

    // Partner Management
    Route::prefix('partners')->name('partners.')->group(function () {
        Route::get('/', [PartnerController::class, 'index'])->name('index');
        Route::get('/create', [PartnerController::class, 'create'])->name('create');
        Route::post('/', [PartnerController::class, 'store'])->name('store');
        Route::get('/{partner}', [PartnerController::class, 'show'])->name('show');
        Route::get('/{partner}/edit', [PartnerController::class, 'edit'])->name('edit');
        Route::put('/{partner}', [PartnerController::class, 'update'])->name('update');
        Route::delete('/{partner}', [PartnerController::class, 'destroy'])->name('destroy');
    });

    // Vendor Management
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('/create', [VendorController::class, 'create'])->name('create');
        Route::post('/', [VendorController::class, 'store'])->name('store');
        Route::get('/{vendor}', [VendorController::class, 'show'])->name('show');
        Route::get('/{vendor}/edit', [VendorController::class, 'edit'])->name('edit');
        Route::put('/{vendor}', [VendorController::class, 'update'])->name('update');
        Route::delete('/{vendor}', [VendorController::class, 'destroy'])->name('destroy');
        Route::patch('/{vendor}/toggle-status', [VendorController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Resource Review Routes
    Route::prefix('resources/review')->name('resources.review.')->group(function () {
        Route::get('/', [ResourceReviewController::class, 'index'])->name('index');
        Route::get('/{resource}', [ResourceReviewController::class, 'show'])->name('show');
        Route::get('/{resource}/edit', [ResourceReviewController::class, 'edit'])->name('edit');
        Route::put('/{resource}', [ResourceReviewController::class, 'update'])->name('update');
        Route::patch('/{resource}/approve', [ResourceReviewController::class, 'approve'])->name('approve');
        Route::patch('/{resource}/reject', [ResourceReviewController::class, 'reject'])->name('reject');
        Route::patch('/{resource}/publish', [ResourceReviewController::class, 'publish'])->name('publish');
        Route::patch('/{resource}/unpublish', [ResourceReviewController::class, 'unpublish'])->name('unpublish');
        Route::patch('/{resource}/mark-under-review', [ResourceReviewController::class, 'markUnderReview'])->name('mark-under-review');
    });

    // Resource Management Routes
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::get('/', [ResourceController::class, 'index'])->name('index');
        Route::get('/create', [ResourceController::class, 'create'])->name('create');
        Route::post('/', [ResourceController::class, 'store'])->name('store');
        Route::get('/{resource}/edit', [ResourceController::class, 'edit'])->name('edit');
        Route::put('/{resource}', [ResourceController::class, 'update'])->name('update');
        Route::delete('/{resource}', [ResourceController::class, 'destroy'])->name('destroy');
    });
    
    // =====================================================================
    // UPDATED: Resource Applications - Admin Oversight (Streamlined)
    // =====================================================================
    Route::prefix('applications')->name('applications.')->group(function () {
        // Main listing with filters
        Route::get('/', [ResourceApplicationController::class, 'index'])->name('index');
        
        // NEW: Analytics dashboard for oversight
        Route::get('/analytics', [ResourceApplicationController::class, 'analytics'])->name('analytics');
        
        // Export functionality
        Route::get('/export', [ResourceApplicationController::class, 'export'])->name('export');
        
        // Individual application view
        Route::get('/{application}', [ResourceApplicationController::class, 'show'])->name('show');
        
        // Admin actions (primarily for ministry resources or emergency override)
        Route::post('/{application}/grant', [ResourceApplicationController::class, 'grant'])->name('grant');
        Route::post('/{application}/decline', [ResourceApplicationController::class, 'decline'])->name('decline');
        Route::post('/{application}/fulfill', [ResourceApplicationController::class, 'fulfill'])->name('fulfill');
        
        // NEW: Payment verification tool for admin oversight
        Route::get('/{application}/verify-payment', [ResourceApplicationController::class, 'verifyPayment'])->name('verify-payment');
        
        // Bulk operations
        Route::post('/bulk-update', [ResourceApplicationController::class, 'bulkUpdate'])->name('bulk-update');
    });

    // Alternative naming for backward compatibility (keeping both paths)
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [ResourceApplicationController::class, 'index'])->name('index');
            Route::get('/analytics', [ResourceApplicationController::class, 'analytics'])->name('analytics');
            Route::get('/export', [ResourceApplicationController::class, 'export'])->name('export');
            Route::get('/{application}', [ResourceApplicationController::class, 'show'])->name('show');
            Route::post('/{application}/grant', [ResourceApplicationController::class, 'grant'])->name('grant');
            Route::post('/{application}/decline', [ResourceApplicationController::class, 'decline'])->name('decline');
            Route::post('/{application}/fulfill', [ResourceApplicationController::class, 'fulfill'])->name('fulfill');
            Route::get('/{application}/verify-payment', [ResourceApplicationController::class, 'verifyPayment'])->name('verify-payment');
            Route::post('/bulk-update', [ResourceApplicationController::class, 'bulkUpdate'])->name('bulk-update');
        });
    });

    // Cooperative Viewing
    Route::prefix('cooperatives')->name('cooperatives.')->group(function () {
        Route::get('/', [CooperativeViewController::class, 'index'])
            ->name('index')
            ->middleware('permission:view_all_cooperatives');
        Route::get('/{cooperative}', [CooperativeViewController::class, 'show'])
            ->name('show')
            ->middleware('permission:view_cooperative_details');
        Route::get('/export', [CooperativeViewController::class, 'export'])
            ->name('export')
            ->middleware('permission:export_cooperatives');
    });

    // Marketplace Management
    Route::prefix('marketplace')->name('marketplace.')->middleware('permission:manage_supplier_catalog')->group(function () {
        Route::get('/dashboard', [MarketplaceAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/listings', [MarketplaceAdminController::class, 'listings'])->name('listings');
        Route::post('/listings/{listing}/approve', [MarketplaceAdminController::class, 'approveListing'])->name('approve');
        Route::post('/listings/{listing}/reject', [MarketplaceAdminController::class, 'rejectListing'])->name('reject');
        Route::delete('/listings/{listing}', [MarketplaceAdminController::class, 'removeListing'])->name('remove');
        Route::get('/categories', [MarketplaceAdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [MarketplaceAdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [MarketplaceAdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [MarketplaceAdminController::class, 'deleteCategory'])->name('categories.destroy');
        Route::get('/subscriptions', [MarketplaceAdminController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/subscriptions/export', [MarketplaceAdminController::class, 'exportSubscriptions'])->name('subscriptions.export');
        Route::get('/analytics', [MarketplaceAdminController::class, 'analytics'])->name('analytics');
        Route::get('/reports/export', [MarketplaceAdminController::class, 'exportReport'])->name('reports.export');
    });

    // Support
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
| LGA Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'permission:view_lga_dashboard'])->prefix('lga-admin')->name('lga_admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [LGAAdminDashboardController::class, 'index'])->name('dashboard');

    // Enrollment Agents Management
    Route::prefix('agents')->name('agents.')->middleware('permission:manage_lga_agents')->group(function () {
        Route::get('/', [LGAAdminManagementController::class, 'index'])->name('index');
        Route::get('/create', [LGAAdminManagementController::class, 'create'])->name('create');
        Route::post('/', [LGAAdminManagementController::class, 'store'])->name('store');
        Route::get('/{agent}/edit', [LGAAdminManagementController::class, 'edit'])->name('edit');
        Route::put('/{agent}', [LGAAdminManagementController::class, 'update'])->name('update');
        Route::delete('/{agent}', [LGAAdminManagementController::class, 'destroy'])->name('destroy');
    });

    // Farmer Review
    Route::prefix('farmers')->name('farmers.')->group(function () {
        Route::get('/', [FarmerReviewController::class, 'index'])->name('index');
        Route::get('/{farmer}', [FarmerReviewController::class, 'show'])->name('show');
        Route::post('/{farmer}/approve', [FarmerReviewController::class, 'approve'])->name('approve');
        Route::post('/{farmer}/reject', [FarmerReviewController::class, 'reject'])->name('reject');
        Route::get('/{farmer}/credentials', [FarmerReviewController::class, 'viewCredentials'])->name('view-credentials');
        Route::get('/export', [FarmerReviewController::class, 'export'])->name('export');
    });

    // Cooperative Management
    Route::prefix('cooperatives')->name('cooperatives.')->middleware('permission:manage_lga_cooperatives')->group(function () {
        Route::get('/', [CooperativeController::class, 'index'])->name('index');
        Route::get('/create', [CooperativeController::class, 'create'])->name('create');
        Route::post('/', [CooperativeController::class, 'store'])->name('store');
        Route::get('/{cooperative}', [CooperativeController::class, 'show'])->name('show');
        Route::get('/{cooperative}/edit', [CooperativeController::class, 'edit'])->name('edit');
        Route::put('/{cooperative}', [CooperativeController::class, 'update'])->name('update');
        Route::delete('/{cooperative}', [CooperativeController::class, 'destroy'])->name('destroy');
        Route::get('/{cooperative}/members', [CooperativeController::class, 'members'])->name('members');
        Route::post('/{cooperative}/members/add', [CooperativeController::class, 'addMember'])->name('members.add');
        Route::delete('/{cooperative}/members/{farmer}', [CooperativeController::class, 'removeMember'])->name('members.remove');
        Route::get('/export/excel', [CooperativeController::class, 'export'])->name('export');
    });

    // Support
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
| Enrollment Agent Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Enrollment Agent'])->prefix('enrollment')->name('enrollment.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [EnrollmentDashboardController::class, 'index'])->name('dashboard');
    
    // Farmer Enrollment
    Route::prefix('farmers')->name('farmers.')->group(function () {
        Route::get('/', [EnrollmentFarmerController::class, 'index'])->name('index');
        Route::get('/create', [EnrollmentFarmerController::class, 'create'])->name('create');
        Route::post('/', [EnrollmentFarmerController::class, 'store'])->name('store');
        Route::get('/{farmer}', [EnrollmentFarmerController::class, 'show'])->name('show');
        Route::get('/{farmer}/credentials', [EnrollmentFarmerController::class, 'showCredentials'])->name('credentials');
        Route::get('/{farmer}/edit', [EnrollmentFarmerController::class, 'edit'])->name('edit');
        Route::put('/{farmer}', [EnrollmentFarmerController::class, 'update'])->name('update');
        Route::get('/{farmer}/farmlands/create', [EnrollmentFarmerController::class, 'createFarmLand'])->name('farmlands.create');
        Route::post('/{farmer}/farmlands', [EnrollmentFarmerController::class, 'storeFarmLand'])->name('farmlands.store');
        Route::delete('/{farmer}', [EnrollmentFarmerController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Farmer/User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:User'])->prefix('farmer')->name('farmer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Resources
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::get('/', [UserResourceController::class, 'index'])->name('index');
        Route::get('/track', [UserResourceController::class, 'track'])->name('track');
        Route::get('/{resource}', [UserResourceController::class, 'show'])->name('show');
        Route::get('/{resource}/apply', [UserResourceController::class, 'apply'])->name('apply');
        Route::post('/{resource}/submit', [UserResourceController::class, 'submit'])->name('submit');
        Route::post('/{resource}/payment/initiate', [UserResourceController::class, 'initiatePayment'])->name('payment.initiate');
        
        // Applications
        Route::get('/applications/{application}', [UserResourceController::class, 'showApplication'])->name('applications.show');
        Route::delete('/applications/{application}/cancel', [UserResourceController::class, 'cancelApplication'])->name('cancel');
    });
    
    
    // Payment callback
    Route::get('/payment/callback', [UserResourceController::class, 'handlePaymentCallback'])->name('payment.callback');

    // Marketplace
    Route::prefix('marketplace')->name('marketplace.')->group(function () {
        Route::get('/', [MarketplaceController::class, 'index'])->name('index');
        Route::get('/my-listings', [MarketplaceController::class, 'myListings'])->name('my-listings');
        Route::post('/subscribe', [MarketplaceController::class, 'initiatePayment'])->name('subscribe');
        Route::get('/payment/callback', [MarketplaceController::class, 'handlePaymentCallback'])->name('payment.callback');
        Route::get('/leads', [MarketplaceController::class, 'myLeads'])->name('leads');
        
        Route::middleware('can:create,App\Models\Market\MarketplaceListing')->group(function () {
            Route::get('/create', [MarketplaceController::class, 'create'])->name('create');
            Route::post('/listings', [MarketplaceController::class, 'store'])->name('store');
            Route::get('/listings/{listing}/edit', [MarketplaceController::class, 'edit'])->name('edit');
            Route::put('/listings/{listing}', [MarketplaceController::class, 'update'])->name('update');
            Route::delete('/listings/{listing}', [MarketplaceController::class, 'destroy'])->name('destroy');
        });
    });

    // Support
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
| Public Marketplace Routes
|--------------------------------------------------------------------------
*/
Route::prefix('marketplace')->name('marketplace.')->group(function () {
    Route::get('/', [MarketplaceController::class, 'index'])->name('index');
    Route::get('/listings/{listing}', [MarketplaceController::class, 'show'])->name('show');
    Route::post('/listings/{listing}/contact', [MarketplaceController::class, 'contactFarmer'])->name('contact-farmer');
});

/*
|--------------------------------------------------------------------------
| Analytics Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('analytics')->name('analytics.')->group(function () {
    Route::middleware('can:view_analytics')->group(function () {
        Route::get('/dashboard', [AnalyticsController::class, 'index'])->name('dashboard');
        Route::get('/demographics', [AnalyticsController::class, 'demographics'])->name('demographics');
        Route::get('/production', [AnalyticsController::class, 'production'])->name('production');
        Route::get('/crops', [AnalyticsController::class, 'crops'])->name('crops');
        Route::get('/livestock', [AnalyticsController::class, 'livestock'])->name('livestock');
        Route::get('/cooperatives', [AnalyticsController::class, 'cooperatives'])->name('cooperatives');
        Route::get('/enrollment', [AnalyticsController::class, 'enrollment'])->name('enrollment');
        Route::get('/trends', [AnalyticsController::class, 'trends'])->name('trends');
    });
    
    Route::get('/lga-comparison', [AnalyticsController::class, 'lgaComparison'])
        ->name('lga_comparison')
        ->middleware('role:Super Admin|Governor|State Admin');
    
    Route::get('/agent-performance', [AnalyticsController::class, 'agentPerformance'])
        ->name('agent_performance')
        ->middleware('role:LGA Admin');
    
    Route::get('/export', [AnalyticsController::class, 'export'])
        ->name('export')
        ->middleware('can:export_analytics');
    
    Route::post('/regenerate', [AnalyticsController::class, 'regenerate'])
        ->name('regenerate')
        ->middleware('role:Super Admin|State Admin');

    // Advanced Analytics
    Route::prefix('advanced')->name('advanced.')->middleware('can:view_analytics')->group(function () {
        Route::get('/', [AdvancedAnalyticsController::class, 'index'])->name('index');
        Route::post('/generate', [AdvancedAnalyticsController::class, 'generate'])->name('generate');
        Route::get('/generate', [AdvancedAnalyticsController::class, 'generate'])->name('generate.get');
        Route::get('/export', [AdvancedAnalyticsController::class, 'export'])
            ->name('export')
            ->middleware('can:export_analytics');
        Route::get('/predefined', [AdvancedAnalyticsController::class, 'predefinedReports'])->name('predefined');
        Route::get('/predefined/{reportKey}', [AdvancedAnalyticsController::class, 'runPredefinedReport'])->name('predefined.run');
        Route::post('/comparative', [AdvancedAnalyticsController::class, 'comparative'])->name('comparative');
    });
});

/*
|--------------------------------------------------------------------------
| Commissioner Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Commissioner'])->prefix('commissioner')->name('commissioner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CommissionerDashboardController::class, 'index'])->name('dashboard');
    
    // Policy Insights
    Route::prefix('policy-insights')->name('policy_insights.')->group(function () {
        Route::get('/', [CommissionerPolicyInsightController::class, 'index'])->name('index');
        Route::get('/demographic-analysis', [CommissionerPolicyInsightController::class, 'getDemographicAnalysis'])->name('demographic_analysis');
        Route::get('/youth-engagement', [CommissionerPolicyInsightController::class, 'getYouthEngagement'])->name('youth_engagement');
        Route::get('/yield-projections', [CommissionerPolicyInsightController::class, 'getYieldProjections'])->name('yield_projections');
        Route::get('/production-patterns', [CommissionerPolicyInsightController::class, 'getProductionPatterns'])->name('production_patterns');
    });

    // Trend Analysis
    Route::prefix('trends')->name('trends.')->group(function () {
        Route::get('/', [CommissionerTrendAnalysisController::class, 'index'])->name('index');
        Route::get('/enrollment-trends', [CommissionerTrendAnalysisController::class, 'getEnrollmentTrends'])->name('enrollment_trends');
        Route::get('/production-trends', [CommissionerTrendAnalysisController::class, 'getProductionTrends'])->name('production_trends');
        Route::get('/resource-utilization-trends', [CommissionerTrendAnalysisController::class, 'getResourceUtilizationTrends'])->name('resource_utilization_trends');
        Route::get('/gender-parity-trends', [CommissionerTrendAnalysisController::class, 'getGenderParityTrends'])->name('gender_parity_trends');
    });

    // LGA Comparison
    Route::prefix('lga-comparison')->name('lga_comparison.')->group(function () {
        Route::get('/', [CommissionerLgaComparisonController::class, 'index'])->name('index');
        Route::get('/performance-ranking', [CommissionerLgaComparisonController::class, 'getPerformanceRanking'])->name('performance_ranking');
        Route::get('/capacity-analysis', [CommissionerLgaComparisonController::class, 'getCapacityAnalysis'])->name('capacity_analysis');
        Route::get('/compare-lgas', [CommissionerLgaComparisonController::class, 'compareLgas'])->name('compare_lgas');
        Route::get('/geographic-analysis', [CommissionerLgaComparisonController::class, 'getGeographicAnalysis'])->name('geographic_analysis');
    });

    // Intervention Tracking
    Route::prefix('interventions')->name('interventions.')->group(function () {
        Route::get('/', [CommissionerInterventionTrackingController::class, 'index'])->name('index');
        Route::get('/beneficiary-report', [CommissionerInterventionTrackingController::class, 'getBeneficiaryReport'])->name('beneficiary_report');
        Route::get('/partner-activities', [CommissionerInterventionTrackingController::class, 'getPartnerActivities'])->name('partner_activities');
        Route::get('/coverage-analysis', [CommissionerInterventionTrackingController::class, 'getCoverageAnalysis'])->name('coverage_analysis');
    });
});


/*
|--------------------------------------------------------------------------
| Vendor Manager Routes (FIXED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Vendor Manager'])->prefix('vendor')->name('vendor.')->group(function () {
    // Dashboard with new statistics
    Route::get('/dashboard', [VendorResourceController::class, 'dashboard'])->name('dashboard');
    
    // Team Management
    Route::prefix('team')->name('team.')->group(function () {
        Route::get('/', [VendorTeamController::class, 'index'])->name('index');
        Route::get('/create', [VendorTeamController::class, 'create'])->name('create');
        Route::post('/', [VendorTeamController::class, 'store'])->name('store');
        Route::get('/{teamMember}/edit', [VendorTeamController::class, 'edit'])->name('edit');
        Route::put('/{teamMember}', [VendorTeamController::class, 'update'])->name('update');
        Route::delete('/{teamMember}', [VendorTeamController::class, 'destroy'])->name('destroy');
        Route::patch('/{teamMember}/reset-password', [VendorTeamController::class, 'resetPassword'])->name('reset-password');
    });
    
    // Resource Management
    Route::prefix('resources')->name('resources.')->group(function () {
        // Resource CRUD
        Route::get('/', [VendorResourceController::class, 'index'])->name('index');
        Route::get('/create', [VendorResourceController::class, 'create'])->name('create');
        Route::post('/', [VendorResourceController::class, 'store'])->name('store');
        Route::get('/{resource}', [VendorResourceController::class, 'show'])->name('show');
        Route::get('/{resource}/edit', [VendorResourceController::class, 'edit'])->name('edit');
        Route::put('/{resource}', [VendorResourceController::class, 'update'])->name('update');
        Route::delete('/{resource}', [VendorResourceController::class, 'destroy'])->name('destroy');
        
        // Application Management Routes
        Route::get('/applications/all', [VendorResourceController::class, 'allApplications'])
            ->name('all-applications');
        
        Route::get('/{resource}/applications', [VendorResourceController::class, 'applications'])
            ->name('applications');
        
        // FIXED: Distribution search page - now uses controller method
        Route::get('/{resource}/distribution', [VendorResourceController::class, 'distributionSearch'])
            ->name('distribution.search');
        
        // Application Action Routes
        Route::get('/applications/{application}', [VendorResourceController::class, 'showApplication'])
            ->name('application.show');
        
        Route::post('/applications/{application}/verify-approve', [VendorResourceController::class, 'verifyAndApprove'])
            ->name('application.verify-approve');
        
        Route::post('/applications/{application}/reject', [VendorResourceController::class, 'rejectApplication'])
            ->name('application.reject');
        
        Route::post('/applications/{application}/fulfill', [VendorResourceController::class, 'fulfillApplication'])
            ->name('application.fulfill');
        
        // Unified Farmer Search Endpoint
        Route::post('/search-farmer', [VendorResourceController::class, 'searchFarmer'])
            ->name('search-farmer');
    });
    
    // Analytics & Payouts
    Route::get('/analytics', [VendorAnalyticsController::class, 'index'])->name('analytics');
    Route::post('/analytics/export', [VendorAnalyticsController::class, 'export'])->name('analytics.export');
    Route::get('/payouts', [VendorPayoutController::class, 'index'])->name('payouts');
});

/*
|--------------------------------------------------------------------------
| Distribution Agent Routes (FIXED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Distribution Agent'])->prefix('vendor/distribution')->name('vendor.distribution.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DistributionDashboardController::class, 'index'])->name('dashboard');
    
    // Distribution Fulfillment
    Route::get('/search', [DistributionFulfillmentController::class, 'searchInterface'])->name('search');
    
    // Use vendor's unified search endpoint
    Route::post('/search-farmer', [VendorResourceController::class, 'searchFarmer'])->name('search-farmer');
    
    // Mark application as fulfilled
    Route::post('/applications/{application}/fulfill', [VendorResourceController::class, 'fulfillApplication'])
        ->name('mark-fulfilled');
    
    // View assigned resources
    Route::get('/resources', [DistributionFulfillmentController::class, 'assignedResources'])
        ->name('resources');
    
    // View applications for specific resource
    Route::get('/resources/{resource}/applications', [DistributionFulfillmentController::class, 'resourceApplications'])
        ->name('resource-applications');
    
    // Quick fulfillment interface
    Route::get('/fulfill/{application}', [DistributionFulfillmentController::class, 'fulfillInterface'])
        ->name('fulfill');
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
























/*
|--------------------------------------------------------------------------
| Vendor Manager Routes (FIXED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Vendor Manager'])->prefix('vendor')->name('vendor.')->group(function () {
    // Dashboard with new statistics
    Route::get('/dashboard', [VendorResourceController::class, 'dashboard'])->name('dashboard');
    
    // Team Management
    Route::prefix('team')->name('team.')->group(function () {
        Route::get('/', [VendorTeamController::class, 'index'])->name('index');
        Route::get('/create', [VendorTeamController::class, 'create'])->name('create');
        Route::post('/', [VendorTeamController::class, 'store'])->name('store');
        Route::get('/{teamMember}/edit', [VendorTeamController::class, 'edit'])->name('edit');
        Route::put('/{teamMember}', [VendorTeamController::class, 'update'])->name('update');
        Route::delete('/{teamMember}', [VendorTeamController::class, 'destroy'])->name('destroy');
        Route::patch('/{teamMember}/reset-password', [VendorTeamController::class, 'resetPassword'])->name('reset-password');
    });
    
    // Resource Management
    Route::prefix('resources')->name('resources.')->group(function () {
        // Resource CRUD
        Route::get('/', [VendorResourceController::class, 'index'])->name('index');
        Route::get('/create', [VendorResourceController::class, 'create'])->name('create');
        Route::post('/', [VendorResourceController::class, 'store'])->name('store');
        Route::get('/{resource}', [VendorResourceController::class, 'show'])->name('show');
        Route::get('/{resource}/edit', [VendorResourceController::class, 'edit'])->name('edit');
        Route::put('/{resource}', [VendorResourceController::class, 'update'])->name('update');
        Route::delete('/{resource}', [VendorResourceController::class, 'destroy'])->name('destroy');
        
        // Application Management Routes
        Route::get('/applications/all', [VendorResourceController::class, 'allApplications'])
            ->name('all-applications');
        
        Route::get('/{resource}/applications', [VendorResourceController::class, 'applications'])
            ->name('applications');
        
        // FIXED: Distribution search page - now uses controller method
        Route::get('/{resource}/distribution', [VendorResourceController::class, 'distributionSearch'])
            ->name('distribution.search');
        
        // Application Action Routes
        Route::get('/applications/{application}', [VendorResourceController::class, 'showApplication'])
            ->name('application.show');
        
        Route::post('/applications/{application}/verify-approve', [VendorResourceController::class, 'verifyAndApprove'])
            ->name('application.verify-approve');
        
        Route::post('/applications/{application}/reject', [VendorResourceController::class, 'rejectApplication'])
            ->name('application.reject');
        
        Route::post('/applications/{application}/fulfill', [VendorResourceController::class, 'fulfillApplication'])
            ->name('application.fulfill');
        
        // Unified Farmer Search Endpoint
        Route::post('/search-farmer', [VendorResourceController::class, 'searchFarmer'])
            ->name('search-farmer');
    });
    
    // Analytics & Payouts
    Route::get('/analytics', [VendorAnalyticsController::class, 'index'])->name('analytics');
    Route::post('/analytics/export', [VendorAnalyticsController::class, 'export'])->name('analytics.export');
    Route::get('/payouts', [VendorPayoutController::class, 'index'])->name('payouts');
});

/*
|--------------------------------------------------------------------------
| Distribution Agent Routes (FIXED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Distribution Agent'])->prefix('vendor/distribution')->name('vendor.distribution.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DistributionDashboardController::class, 'index'])->name('dashboard');
    
    // Distribution Fulfillment
    Route::get('/search', [DistributionFulfillmentController::class, 'searchInterface'])->name('search');
    
    // Use vendor's unified search endpoint
    Route::post('/search-farmer', [VendorResourceController::class, 'searchFarmer'])->name('search-farmer');
    
    // Mark application as fulfilled
    Route::post('/applications/{application}/fulfill', [VendorResourceController::class, 'fulfillApplication'])
        ->name('mark-fulfilled');
    
    // View assigned resources
    Route::get('/resources', [DistributionFulfillmentController::class, 'assignedResources'])
        ->name('resources');
    
    // View applications for specific resource
    Route::get('/resources/{resource}/applications', [DistributionFulfillmentController::class, 'resourceApplications'])
        ->name('resource-applications');
    
    // Quick fulfillment interface
    Route::get('/fulfill/{application}', [DistributionFulfillmentController::class, 'fulfillInterface'])
        ->name('fulfill');
});