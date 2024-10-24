<?php

use App\Http\Controllers\ComponentController;
use App\Http\Controllers\OfficeDocsController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\EsoCourtController;
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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::controller(MenuController::class)->group(function () {
    Route::get('headerMenu/{lang?}', 'apiHeaderMenu');
    Route::get('footerMenu/{lang?}', 'apiFooterMenu');
});

Route::controller(NewsController::class)->group(function () {
    Route::get('headerNews/{lang?}', 'apiHeaderNews');
    Route::get('newsDescription/{lang?}', 'apiNewsDescription');
    Route::get('newsDescriptionById/{id}/{lang?}', 'newsDescriptionById');
});

Route::controller(ComponentController::class)->group(function () {
    Route::get('componentData/{componentName}/{lang?}', 'getComponentData');
    Route::get('servicesData/{lang?}',  'getServiceComponentData');
});

Route::get('officeDocs/{categoryId}/{lang?}', [OfficeDocsController::class, 'getOfficeData']);

Route::get('directory/{lang?}', [DirectoryController::class, 'apiDirectory']);
Route::get('esocourt/{lang?}', [EsoCourtController::class, 'apiEsoCourt']);

