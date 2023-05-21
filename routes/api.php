<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/getInventory', [\App\Http\Controllers\InventoriesController::class, 'getInventory']);
Route::get('/getExpiringInventory', [\App\Http\Controllers\InventoriesController::class, 'getExpiringInventory']);
Route::get('/getExpiredInventory', [\App\Http\Controllers\InventoriesController::class, 'getExpiredInventory']);
Route::get('/searchInventory/{key}', [\App\Http\Controllers\InventoriesController::class, 'searchInventory']);
Route::get('/getScanLogs', [\App\Http\Controllers\InventoriesController::class, 'getScanLogs']);
Route::post('/updateInventory', [\App\Http\Controllers\InventoriesController::class, 'store']);

Route::post('/scanLogs', [\App\Http\Controllers\InventoriesController::class, 'processLogs']);

Route::get('updateScanLogs', [\App\Http\Controllers\InventoriesController::class, 'updateScanLogs']);

Route::delete('deleteExpiredInventory', [\App\Http\Controllers\InventoriesController::class, 'deleteExpiredInventory']);
Route::delete('deleteSelected/{id}', [\App\Http\Controllers\InventoriesController::class, 'destroy']);
