<?php

use App\Http\Controllers\Api\IuranTransactionController;
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

Route::middleware('api.key')->group(function () {
    Route::get('/iuran', [IuranTransactionController::class, 'index']);
    Route::post('/iuran', [IuranTransactionController::class, 'store']);
    Route::get('/iuran/{iuran}', [IuranTransactionController::class, 'show']);
    Route::delete('/iuran/{iuran}', [IuranTransactionController::class, 'destroy']);
});
