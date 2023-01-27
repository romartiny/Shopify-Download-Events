<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController;

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

Route::middleware(['verify.shopify'])->group(function () {
    Route::get('/', [OrdersController::class, 'showDownloadPage'])
        ->middleware(['verify.shopify'])->name('home');
    Route::get('/download', [OrdersController::class, 'createOrderCSV'])
        ->middleware(['verify.shopify'])->name('download');
});