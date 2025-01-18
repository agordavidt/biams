<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmersController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authenticated and verified routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Default dashboard for regular users
    // Route::get('dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');


    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Agricultural Practices Registration forms
Route::middleware(['auth', 'verified'])->group(function () {
    // Crop Farmer Routes
    Route::get('/farmers/crop', [FarmersController::class, 'showCropFarmerForm'])->name('farmers.crop');
    Route::post('/farmers/crop', [FarmersController::class, 'storeCropFarmer'])->name('farmers.crop.store');

    // Animal Farmer Routes
    Route::get('/farmers/animal', [FarmersController::class, 'showAnimalFarmerForm'])->name('farmers.animal');
    Route::post('/farmers/animal', [FarmersController::class, 'storeAnimalFarmer'])->name('farmers.animal.store');

    // Abattoir Operator Routes
    Route::get('/farmers/abattoir', [FarmersController::class, 'showAbattoirOperatorForm'])->name('farmers.abattoir');
    Route::post('/farmers/abattoir', [FarmersController::class, 'storeAbattoirOperator'])->name('farmers.abattoir.store');

    // Processor Routes
    Route::get('/farmers/processor', [FarmersController::class, 'showProcessorForm'])->name('farmers.processor');
    Route::post('/farmers/processor', [FarmersController::class, 'storeProcessor'])->name('farmers.processor.store');
});





// Admin routes

Route::middleware(['auth', 'admin'])->group(function () {
    // Crop Farmers Applications
    Route::get('/admin/applications/crop-farmers', [AdminController::class, 'cropFarmers'])->name('admin.applications.crop-farmers');

    // Animal Farmers Applications
    Route::get('/admin/applications/animal-farmers', [AdminController::class, 'animalFarmers'])->name('admin.applications.animal-farmers');

    // Abattoir Operators Applications
    Route::get('/admin/applications/abattoir-operators', [AdminController::class, 'abattoirOperators'])->name('admin.applications.abattoir-operators');

    // Processors Applications
    Route::get('/admin/applications/processors', [AdminController::class, 'processors'])->name('admin.applications.processors');

    // Approve/Reject Actions
    Route::post('/admin/applications/{user}/approve', [AdminController::class, 'approve'])->name('admin.applications.approve');

    Route::post('/admin/applications/{user}/reject', [AdminController::class, 'reject'])->name('admin.applications.reject');
});





// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin/applications', [AdminController::class, 'applicationIndex'])->name('admin.applications');
//     Route::post('/admin/applications/{user}/approve', [AdminController::class, 'approve'])->name('admin.applications.approve');
//     Route::post('/admin/applications/{user}/reject', [AdminController::class, 'reject'])->name('admin.applications.reject');
// });































// Include authentication routes
require __DIR__.'/auth.php';


