<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmersController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\UserResourceController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});


Route::get('/horizontal', function () {
    return view('horizontal');
});


// Authenticated and verified routes
Route::middleware(['auth', 'verified'])->group(function () { 
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // show application details to users
    Route::get('/application/{id}/details', [DashboardController::class, 'showApplicationDetails'])->name('application.details');
    
    });

// Profile Completion (for new users)
Route::middleware(['auth', 'verified', 'profile.incomplete'])->group(function () {
    Route::get('/profile/complete', [ProfileController::class, 'showCompleteForm'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'complete']);
});

// Profile Management (for existing users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





Route::middleware(['auth', 'verified', 'onboarded'])->group(function () {
    // agricultural practices routes
    Route::get('/farmers/crop', [FarmersController::class, 'showCropFarmerForm'])->name('farmers.crop');
    Route::post('/farmers/crop', [FarmersController::class, 'storeCropFarmer'])->name('farmers.crop.store');
    Route::get('/farmers/animal', [FarmersController::class, 'showAnimalFarmerForm'])->name('farmers.animal');
    Route::post('/farmers/animal', [FarmersController::class, 'storeAnimalFarmer'])->name('farmers.animal.store');
    Route::get('/farmers/abattoir', [FarmersController::class, 'showAbattoirOperatorForm'])->name('farmers.abattoir');
    Route::post('/farmers/abattoir', [FarmersController::class, 'storeAbattoirOperator'])->name('farmers.abattoir.store');
    Route::get('/farmers/processor', [FarmersController::class, 'showProcessorForm'])->name('farmers.processor');
    Route::post('/farmers/processor', [FarmersController::class, 'storeProcessor'])->name('farmers.processor.store');

    Route::get('/farmers/submissions', [FarmersController::class, 'showSubmissions'])->name('farmers.submissions');

    // resources routes
    Route::get('/resources', [UserResourceController::class, 'index'])->name('user.resources.index');
    Route::get('/resources/{resource}', [UserResourceController::class, 'show'])->name('user.resources.show');
    Route::get('/resources/{resource}/apply', [UserResourceController::class, 'apply'])->name('user.resources.apply');
    Route::post('/resources/{resource}/submit', [UserResourceController::class, 'submit'])->name('user.resources.submit');
    Route::get('/resources/applications/track', [UserResourceController::class, 'track'])->name('user.resources.track');
});



// Delete this routes Admin routes

Route::middleware(['auth', 'admin'])->group(function () { 
    Route::get('/admin/applications/crop-farmers', [AdminController::class, 'cropFarmers'])->name('admin.applications.crop-farmers');   
    Route::get('/admin/applications/animal-farmers', [AdminController::class, 'animalFarmers'])->name('admin.applications.animal-farmers');    
    Route::get('/admin/applications/abattoir-operators', [AdminController::class, 'abattoirOperators'])->name('admin.applications.abattoir-operators'); 
    Route::get('/admin/applications/processors', [AdminController::class, 'processors'])->name('admin.applications.processors');
    Route::post('/applications/{type}/{id}/approve', [AdminController::class, 'approve'])->name('admin.applications.approve');
Route::post('/applications/{type}/{id}/reject', [AdminController::class, 'reject'])->name('admin.applications.reject');
});


Route::middleware(['auth', 'admin'])->group(function () {
    // Admin dashboard
    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Add a new user (e.g., admin)
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    // Onboard users (approve profiles)
    Route::post('/admin/users/{user}/onboard', [AdminController::class, 'onboardUser'])->name('admin.users.onboard');
    // View user summary
    Route::get('/admin/users/summary', [AdminController::class, 'userSummary'])->name('admin.users.summary');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    // Send notifications
    Route::post('/admin/users/{user}/notify', [AdminController::class, 'sendNotification'])->name('admin.users.notify');
});


// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/registrations', [AdminController::class, 'showRegistrations'])->name('admin.registrations.index');
    Route::put('/admin/registrations/{registration_id}', [AdminController::class, 'updateRegistrationStatus'])->name('admin.registrations.update');
   
    // manage resources
    // Route::resource('admin/resources', ResourceController::class);
    // // Route::resource('admin/resources', ResourceController::class, ['as' => 'admin']);
    Route::get('/admin/resources', [ResourceController::class, 'index'])->name('admin.resources.index');
    Route::get('/admin/resources/create', [ResourceController::class, 'create'])->name('admin.resources.create');
    Route::post('/admin/resources', [ResourceController::class, 'store'])->name('admin.resources.store');
    Route::get('/admin/resources/{resource}/edit', [ResourceController::class, 'edit'])->name('admin.resources.edit');
    Route::put('/admin/resources/{resource}', [ResourceController::class, 'update'])->name('admin.resources.update'); // or PATCH
    Route::delete('/admin/resources/{resource}', [ResourceController::class, 'destroy'])->name('admin.resources.destroy');

});



// Include authentication routes
require __DIR__.'/auth.php';





