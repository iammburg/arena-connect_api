<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{FieldCentreController, UserController, FacilityController, FieldController, FieldPriceScheduleController};
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

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard')->middleware('auth');

// Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::middleware(['role:Admin Lapangan,Admin Aplikasi'])->group(function () {
            Route::resource('/field-centres', FieldCentreController::class);
            Route::resource('/fields', FieldController::class);
            Route::resource('/field-price-schedules', FieldPriceScheduleController::class);
        });

        Route::middleware(['role:Admin Aplikasi'])->group(function () {
            Route::resource('/users', UserController::class);
            Route::resource('/facilities', FacilityController::class);
        });

        // Route::middleware(['role:Admin Lapangan'])->group(function () {
        //     Route::resource('/field-price-schedules', FieldPriceScheduleController::class);
        // });
    });
});