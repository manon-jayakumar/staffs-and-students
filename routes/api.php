<?php

use App\Http\Controllers\Api\TestController;
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

Route::name('api.')->group(function () {
    Route::prefix('tests')->name('tests.')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index');
        Route::post('/', [TestController::class, 'store'])->name('store');
        Route::put('/', [TestController::class, 'update'])->name('update');
    });
});