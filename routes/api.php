<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




// Routes for Resources endpoints

Route::middleware(['auth:sanctum'])->prefix('api/v1')->name('api.')->group(function () {
    
    // Vendor API endpoints for mobile/distributor apps
    Route::middleware(['role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::post('/applications/{application}/verify-approve', 
            [VendorResourceController::class, 'verifyAndApprove']);
        Route::post('/applications/{application}/fulfill', 
            [VendorResourceController::class, 'fulfillApplication']);
        Route::post('/search-farmer', 
            [VendorResourceController::class, 'searchFarmer']);
        Route::get('/dashboard-stats', 
            [VendorResourceController::class, 'dashboardStats']);
    });
    
    // Admin API endpoints
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/applications/{application}/verify-payment', 
            [AdminResourceApplicationController::class, 'verifyPayment']);
        Route::get('/analytics-data', 
            [AdminResourceApplicationController::class, 'analyticsData']);
    });
});