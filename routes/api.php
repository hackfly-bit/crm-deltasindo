<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Profile API Routes
Route::middleware(['web'])->prefix('profile')->group(function () {
    Route::get('/{id}/dashboard-data', [ProfileApiController::class, 'getDashboardData'])->name('api.profile.dashboard-data');
    Route::put('/{id}/update', [ProfileApiController::class, 'updateProfile'])->name('api.profile.update');
    Route::get('/roles', [ProfileApiController::class, 'getRoles'])->name('api.profile.roles');
    
    // Separate component endpoints
    Route::get('/{id}/kpi-weekly', [ProfileApiController::class, 'getKpiWeekly'])->name('api.profile.kpi-weekly');
    Route::get('/{id}/kpi-monthly', [ProfileApiController::class, 'getKpiMonthly'])->name('api.profile.kpi-monthly');
    Route::get('/{id}/brand-chart', [ProfileApiController::class, 'getBrandChart'])->name('api.profile.brand-chart');
    Route::get('/{id}/product-chart', [ProfileApiController::class, 'getProductChart'])->name('api.profile.product-chart');
    Route::get('/{id}/total-data', [ProfileApiController::class, 'getTotalDataOnly'])->name('api.profile.total-data');
    Route::get('/{id}/sales-target', [ProfileApiController::class, 'getSalesTarget'])->name('api.profile.sales-target');
    Route::get('/{id}/user', [ProfileApiController::class, 'getUserProfile'])->name('api.profile.user');
});

Route::get('/data-user', function(){
    $data = User::all();
    return response()->json([
        'status' => 1,
        'data' => $data,
        'message' => 'Hello World'
    ]);
});