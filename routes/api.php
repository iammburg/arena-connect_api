<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FieldCentreController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\FieldController;
use App\Http\Controllers\Api\PaymentsController;
use App\Models\Payments;
use Faker\Provider\ar_EG\Payment;
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
// Route::middleware(['auth:sanctum'])->group(function () {
    // Route::resource('bookings', BookingController::class);
Route::middleware(['auth:sanctum'])->group(function () {
    // Route::resource('payments', PaymentsController::class);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::put('/bookings/{id}/status', [BookingController::class, 'updateStatus']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
});

//  Untuk Payments
Route::middleware(['auth:sanctum'])->group(function () {
    // Route::resource('payments', PaymentsController::class);
    Route::get('/payments', [PaymentsController::class, 'index']);
    Route::post('/payments', [PaymentsController::class, 'store']);
    // Route::put('/payments/{id}', [PaymentsController::class, 'updateStatus']);
    // Route::delete('/payments/{id}', [PaymentsController::class, 'destroy']);
});

// Buat Show Payments
Route::get('/total-revenue',[PaymentsController::class, 'getTotalRevenue']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('/users', AuthController::class);
    Route::resource('payments', PaymentsController::class);
Route::put('/payments/{id}', [PaymentsController::class, 'updateStatus']);
//     Route::get('payments/{id}', [PaymentsController::class, 'show']);
    Route::post('payments/{id}', [PaymentsController::class, 'updatePayment']);
    Route::get('payments/{field_centre_id}/banks', [PaymentsController::class, 'getBanksByFieldCentreId']);
});


