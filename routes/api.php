<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FieldCentreController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\FieldController;
use App\Http\Controllers\Api\PaymentsController;
use App\Models\Payments;
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

Route::get('/', function () {
    return response()->json([
        'status' => false,
        'message' => 'Unauthorized access'
    ], 401);
})->name('login');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Untuk Field Centres
Route::resource('field-centres', FieldCentreController::class);
Route::resource('facilities', FacilityController::class);
Route::resource('fields', FieldController::class);
Route::get('/field-centres/{fieldCentreId}/fields', [FieldController::class, 'indexByFieldCentre']);
Route::resource('payments', PaymentsController::class);

// Untuk Bookings
Route::resource('bookings', BookingController::class);

//bukti Show payments
Route::get('payments/{id}', [PaymentsController::class, 'show']);
Route::resource('/users', AuthController::class);
