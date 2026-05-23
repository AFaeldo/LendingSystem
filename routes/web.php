<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect('/login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [AuthController::class, 'forgotPage'])->name('forgot.password');
    Route::post('/forgot-password', [AuthController::class, 'sendReset']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED SYSTEM ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | LENDING MODULE
    |--------------------------------------------------------------------------
    */

    Route::resource('lendings', LendingController::class);


    /*
    |--------------------------------------------------------------------------
    | RETURNS MODULE
    |--------------------------------------------------------------------------
    */

    Route::resource('returns', ReturnController::class)->only(['index','create','store','show']);


    /*
    |--------------------------------------------------------------------------
    | INVENTORY / ITEMS MODULE
    |--------------------------------------------------------------------------
    */

    Route::resource('items', InventoryItemController::class);


    /*
    |--------------------------------------------------------------------------
    | BORROWERS MODULE
    |--------------------------------------------------------------------------
    */

    Route::resource('borrowers', BorrowerController::class);


    /*
    |--------------------------------------------------------------------------
    | REPORTS MODULE
    |--------------------------------------------------------------------------
    */

    Route::resource('reports', ReportController::class)->only(['index','show']);

});
