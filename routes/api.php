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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('/field-centres', FieldCentreController::class)->middleware('auth:sanctum');
    Route::get('/field-centres/{fieldCentreId}/fields', [FieldController::class, 'indexByFieldCentre']);

    Route::resource('fields', FieldController::class);

    Route::resource('/users', AuthController::class);

    Route::resource('facilities', FacilityController::class);

    Route::resource('bookings', BookingController::class);

    Route::resource('payments', PaymentsController::class);
    Route::get('payments/{id}', [PaymentsController::class, 'show']);
    Route::post('payments/{id}', [PaymentsController::class, 'updatePayment']);
    Route::get('payments/{field_centre_id}/banks', [PaymentsController::class, 'getBanksByFieldCentreId']);
    Route::get('payments/user/{user_id}', [PaymentsController::class, 'getPaymentByUser']);
});

Route::put('/payment-status/{id}', [PaymentsController::class, 'updateStatus']);
Route::get('/total-revenue', [PaymentsController::class, 'getTotalRevenue']);
Route::get('/field-centres/user/{userId}', [FieldCentreController::class, 'getFieldByUserId']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
});