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
        'message' => 'Unauthorized access',
    ], 401);
})->name('login');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Buat Field Centres
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('field-centres', FieldCentreController::class);
    Route::resource('facilities', FacilityController::class);
    Route::resource('fields', FieldController::class);
    Route::get('/field-centres/{fieldCentreId}/fields', [FieldController::class, 'indexByFieldCentre']);
});

// Buat Bookings
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('bookings', BookingController::class);
});
// Buat Show Payments
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('/users', AuthController::class);
    Route::resource('payments', PaymentsController::class);
    Route::get('payments/{id}', [PaymentsController::class, 'show']);
    Route::post('payments/{id}', [PaymentsController::class, 'updatePayment']);
    Route::get('payments/{field_centre_id}/banks', [PaymentsController::class, 'getBanksByFieldCentreId']);
});


