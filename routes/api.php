<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    RegistrationController,
    VoucherController
};

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [RegistrationController::class, 'register']);

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth:api');;
});

Route::prefix('/voucher')
    ->middleware(['auth:api'])
    ->controller(VoucherController::class)->group(function () {
        Route::get('/list', 'list')->name('list');
        Route::get('/generate', 'generate')->name('generate');
        Route::delete('/delete', 'delete')->name('delete');
});
