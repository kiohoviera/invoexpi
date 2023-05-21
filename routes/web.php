<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\InventoriesController::class, 'index']);
Route::get('createInventory', [\App\Http\Controllers\InventoriesController::class, 'createInventory']);
Route::get('expiringInventory', [\App\Http\Controllers\InventoriesController::class, 'expiringInventory']);
Route::get('getExpiredInventory', [\App\Http\Controllers\InventoriesController::class, 'getExpiredInventory']);
Route::get('uploadInventory', [\App\Http\Controllers\InventoriesController::class, 'uploadInventory']);
Route::post('/import', [\App\Http\Controllers\InventoriesController::class, 'importInventory']);

Route::get('/optimize', function (){
   \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});
