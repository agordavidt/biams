<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmersController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\UserResourceController; 
use App\Http\Controllers\Admin\ResourceApplicationController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MarketplaceMessageController;
use App\Http\Controllers\Admin\MarketplaceAdminController;
use App\Http\Controllers\MarketplaceVisitorController;
use Illuminate\Support\Facades\Route;

/*------------------------------------------
| Public Routes
|------------------------------------------*/
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

// Marketplace Visitor Routes
Route::get('visitor/marketplace', [MarketplaceVisitorController::class, 'index'])
    ->name('visitor.marketplace');
Route::get('visitor/marketplace/{listing}', [MarketplaceVisitorController::class, 'show'])
    ->name('visitor.marketplace.show');

/*------------------------------------------
| Authentication & Profile Routes
|------------------------------------------*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/horizontal', function () {
        return view('horizontal');
    });
    Route::get('/application/{id}/details', [DashboardController::class, 'showApplicationDetails'])
        ->name('application.details');
});

// Profile Completion
Route::middleware(['auth', 'verified', 'profile.incomplete'])->group(function () {
    Route::get('/profile/complete', [ProfileController::class, 'showCompleteForm'])
        ->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'complete']);
});

// Profile Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*------------------------------------------
| Farmer & Resource Routes (Onboarded Users)
|------------------------------------------*/
Route::middleware(['auth', 'verified', 'onboarded'])->group(function () {
    // Agricultural Practices
    Route::get('/farmers/crop', [FarmersController::class, 'showCropFarmerForm'])->name('farmers.crop');
    Route::post('/farmers/crop', [FarmersController::class, 'storeCropFarmer'])->name('farmers.crop.store');
    Route::get('/farmers/animal', [FarmersController::class, 'showAnimalFarmerForm'])->name('farmers.animal');
    Route::post('/farmers/animal', [FarmersController::class, 'storeAnimalFarmer'])->name('farmers.animal.store');
    Route::get('/farmers/abattoir', [FarmersController::class, 'showAbattoirOperatorForm'])->name('farmers.abattoir');
    Route::post('/farmers/abattoir', [FarmersController::class, 'storeAbattoirOperator'])->name('farmers.abattoir.store');
    Route::get('/farmers/processor', [FarmersController::class, 'showProcessorForm'])->name('farmers.processor');
    Route::post('/farmers/processor', [FarmersController::class, 'storeProcessor'])->name('farmers.processor.store');
    Route::get('/farmers/submissions', [FarmersController::class, 'showSubmissions'])->name('farmers.submissions');

    // Resources
    Route::get('/resources', [UserResourceController::class, 'index'])->name('user.resources.index');
    Route::get('/resources/{resource}', [UserResourceController::class, 'show'])->name('user.resources.show');
    Route::get('/resources/{resource}/apply', [UserResourceController::class, 'apply'])->name('user.resources.apply');
    Route::post('/resources/{resource}/submit', [UserResourceController::class, 'submit'])->name('user.resources.submit');
    Route::get('/resources/applications/track', [UserResourceController::class, 'track'])->name('user.resources.track');
});

/*------------------------------------------
| Admin Routes
|------------------------------------------*/
Route::middleware(['auth', 'admin'])->group(function () {
    // Farmer Practice Management
    Route::get('/admin/practices/crop-farmers', [AdminController::class, 'cropFarmers'])
        ->name('admin.practices.crop-farmers');
    Route::get('/admin/practices/animal-farmers', [AdminController::class, 'animalFarmers'])
        ->name('admin.practices.animal-farmers');
    Route::get('/admin/practices/abattoir-operators', [AdminController::class, 'abattoirOperators'])
        ->name('admin.practices.abattoir-operators');
    Route::get('/admin/practices/processors', [AdminController::class, 'processors'])
        ->name('admin.practices.processors');
    Route::post('/applications/{type}/{id}/approve', [AdminController::class, 'approve'])
        ->name('admin.applications.approve');
    Route::post('/admin/applications/{type}/{id}/reject', [AdminController::class, 'reject'])
        ->name('admin.applications.reject');

    // User Management
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/admin/users/{user}/onboard', [AdminController::class, 'onboardUser'])->name('admin.users.onboard');
    Route::post('/admin/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('admin.users.reject');
    Route::get('/admin/users/summary', [AdminController::class, 'userSummary'])->name('admin.users.summary');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/admin/users/{user}/notify', [AdminController::class, 'sendNotification'])->name('admin.users.notify');

    // Registration Management
    Route::get('/admin/registrations', [AdminController::class, 'showRegistrations'])
        ->name('admin.registrations.index');
    Route::put('/admin/registrations/{registration_id}', [AdminController::class, 'updateRegistrationStatus'])
        ->name('admin.registrations.update');

    // Resource Management
    Route::get('/admin/resources', [ResourceController::class, 'index'])->name('admin.resources.index');
    Route::get('/admin/resources/create', [ResourceController::class, 'create'])->name('admin.resources.create');
    Route::post('/admin/resources', [ResourceController::class, 'store'])->name('admin.resources.store');
    Route::get('/admin/resources/{resource}/edit', [ResourceController::class, 'edit'])->name('admin.resources.edit');
    Route::put('/admin/resources/{resource}', [ResourceController::class, 'update'])->name('admin.resources.update');
    Route::delete('/admin/resources/{resource}', [ResourceController::class, 'destroy'])->name('admin.resources.destroy');

    // Resource Applications
    Route::get('/admin/resources/applications', [ResourceApplicationController::class, 'index'])
        ->name('admin.applications.index');
    Route::get('/admin/resources/applications/{application}', [ResourceApplicationController::class, 'show'])
        ->name('admin.applications.show');
    Route::put('/admin/resources/applications/{application}/status', [ResourceApplicationController::class, 'updateStatus'])
        ->name('admin.applications.update-status');
});

/*------------------------------------------
| Super Admin Routes
|------------------------------------------*/
Route::middleware(['auth', 'super_admin'])->group(function () {
    // Dashboard
    Route::get('/super-admin/dashboard', [SuperAdminController::class, 'dashboard'])
        ->name('super_admin.dashboard');

    // User Management
    Route::get('/super-admin/users', [SuperAdminController::class, 'manageUsers'])
        ->name('super_admin.users');
    Route::get('/super-admin/users/create', [SuperAdminController::class, 'createUser'])
        ->name('super_admin.users.create');
    Route::post('/super-admin/users/store', [SuperAdminController::class, 'storeUser'])
        ->name('super_admin.users.store');
    Route::get('/super-admin/users/{user}/edit', [SuperAdminController::class, 'editUser'])
        ->name('super_admin.users.edit');
    Route::put('/super-admin/users/{user}/update', [SuperAdminController::class, 'updateUser'])
        ->name('super_admin.users.update');
    Route::delete('/super-admin/users/{user}/delete', [SuperAdminController::class, 'deleteUser'])
        ->name('super_admin.users.delete');

    // System Configuration
    Route::get('/super-admin/settings', [SuperAdminController::class, 'manageSettings'])
        ->name('super_admin.settings');
    Route::post('/super-admin/settings/update', [SuperAdminController::class, 'updateSettings'])
        ->name('super_admin.settings.update');

    // Security & Logs
    Route::get('/super-admin/activity-logs', [SuperAdminController::class, 'activityLogs'])
        ->name('super_admin.activity_logs');
    Route::post('/super-admin/users/{user}/force-password-reset', [SuperAdminController::class, 'forcePasswordReset'])
        ->name('super_admin.force_password_reset');
    Route::get('/super-admin/error-logs', [SuperAdminController::class, 'errorLogs'])
        ->name('super_admin.error_logs');
    Route::get('/super-admin/audit-logs', [SuperAdminController::class, 'auditLogs'])
        ->name('super_admin.audit_logs');

    // Content Management
    Route::get('/super-admin/content', [SuperAdminController::class, 'manageContent'])
        ->name('super_admin.content');
    Route::post('/super-admin/content/store', [SuperAdminController::class, 'storeContent'])
        ->name('super_admin.content.store');
    Route::put('/super-admin/content/{content}/update', [SuperAdminController::class, 'updateContent'])
        ->name('super_admin.content.update');
    Route::delete('/super-admin/content/{content}/delete', [SuperAdminController::class, 'deleteContent'])
        ->name('super_admin.content.delete');

    // Integrations
    Route::get('/super-admin/integrations', [SuperAdminController::class, 'manageIntegrations'])
        ->name('super_admin.integrations');
    Route::put('/super-admin/integrations/{integration}/update', [SuperAdminController::class, 'updateIntegration'])
        ->name('super_admin.integrations.update');

    // Analytics
    Route::get('/super-admin/analytics', [SuperAdminController::class, 'analytics'])
        ->name('super_admin.analytics');
});

/*------------------------------------------
| Marketplace Routes
|------------------------------------------*/
// User Marketplace (Onboarded Users)
Route::middleware(['auth', 'verified', 'onboarded'])->group(function () {
    // Listings
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/listings/{listing}', [MarketplaceController::class, 'show'])->name('marketplace.show');
    Route::get('/marketplace/my-listings', [MarketplaceController::class, 'myListings'])->name('marketplace.my-listings');
    Route::get('/marketplace/create', [MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store');
    Route::get('/marketplace/edit/{listing}', [MarketplaceController::class, 'edit'])->name('marketplace.edit');
    Route::put('/marketplace/{listing}', [MarketplaceController::class, 'update'])->name('marketplace.update');
    Route::delete('/marketplace/{listing}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');
    Route::patch('/marketplace/{listing}/status', [MarketplaceController::class, 'updateStatus'])
        ->name('marketplace.update-status');

    // Messaging
    Route::get('/marketplace/messages/inbox', [MarketplaceMessageController::class, 'inbox'])
        ->name('marketplace.messages.inbox');
    Route::get('/marketplace/{listing}/messages/{partner_id?}', [MarketplaceMessageController::class, 'showConversation'])
        ->name('marketplace.messages.conversation');
    Route::post('/marketplace/{listing}/messages', [MarketplaceMessageController::class, 'sendMessage'])
        ->name('marketplace.messages.send');
});

// Admin Marketplace Management
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/marketplace/dashboard', [MarketplaceAdminController::class, 'dashboard'])
        ->name('admin.marketplace.dashboard');
    Route::get('/admin/marketplace/listings', [MarketplaceAdminController::class, 'listings'])
        ->name('admin.marketplace.listings');
    Route::delete('/admin/marketplace/listings/{listing}', [MarketplaceAdminController::class, 'removeListing'])
        ->name('admin.marketplace.listings.remove');
    Route::get('/admin/marketplace/categories', [MarketplaceAdminController::class, 'categories'])
        ->name('admin.marketplace.categories');
    Route::post('/admin/marketplace/categories', [MarketplaceAdminController::class, 'storeCategory'])
        ->name('admin.marketplace.categories.store');
    Route::put('/admin/marketplace/categories/{category}', [MarketplaceAdminController::class, 'updateCategory'])
        ->name('admin.marketplace.categories.update');
    Route::delete('/admin/marketplace/categories/{category}', [MarketplaceAdminController::class, 'deleteCategory'])
        ->name('admin.marketplace.categories.delete');
});

// Authentication Routes
require __DIR__.'/auth.php';