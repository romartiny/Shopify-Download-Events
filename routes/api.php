<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController as OrdersController;

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

Route::middleware(['verify.shopify'])->group(function () {
    Route::group(['prefix' => 'v1'], function () {
//    if (Auth::user()) {
//        Route::get('/check', [OrdersController::class, 'getLastOrder'])->middleware(['verify.shopify'])->name('shop');
//    }
//    return Auth::user()->name;
//    Route::get('/check', function () {
//        return Auth::user();
//    });
        Route::get('/check', function () {
            return (new OrdersController())->grabOrders();
        });
    });
});
//
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
