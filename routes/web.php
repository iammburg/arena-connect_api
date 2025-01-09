<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{FieldCentreController, UserController, FacilityController, FieldController, FieldPriceScheduleController, BankController, PaymentController};
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [
    FieldCentreController::class,
    'landingPage',
]);

Auth::routes();


// Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard')->middleware('role:Admin Lapangan,Admin Aplikasi,Customer');
    Route::prefix('dashboard')->group(function () {
        Route::middleware(['role:Admin Lapangan,Admin Aplikasi'])->group(function () {
            Route::resource('/field-centres', FieldCentreController::class);
            Route::resource('/fields', FieldController::class);
            Route::patch('/fields/{field}/update-status', [FieldController::class, 'updateStatus'])->name('fields.update-status');
            Route::resource('/field-price-schedules', FieldPriceScheduleController::class);
            Route::resource('/banks', BankController::class);
            // Route::resource('/payments', PaymentController::class);
            Route::patch('/payments/{payment}/approve', [PaymentController::class, 'approvePayment'])->name('payments.approve');
            Route::patch('/payments/{payment}/reject', [PaymentController::class, 'rejectPayment'])->name('payments.reject');
        });
        Route::middleware(['role:Customer,Admin Lapangan,Admin Aplikasi'])->group(function () {
            Route::resource('/payments', PaymentController::class);
        });

        Route::middleware(['role:Admin Aplikasi'])->group(function () {
            Route::resource('/users', UserController::class);
            Route::resource('/facilities', FacilityController::class);
        });
    });
});