<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemsListController;
use App\Http\Controllers\Api\PublicGrievanceController;
use App\Http\Controllers\Api\ColonyController;
use App\Http\Controllers\Api\RequestController;


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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/public-grievances', [PublicGrievanceController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('items/details', [ItemsListController::class, 'getItemsDetails']);
    Route::post('logistic/user-request-store', [RequestController::class, 'store']);
    Route::post('logistic/user-request-update/{requestId}', [RequestController::class, 'update']);
    Route::get('logistic/user-history', [RequestController::class, 'requestHistory']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::get('/colonylist', [ColonyController::class, 'getAllcolonies'])->name('colonylist');

