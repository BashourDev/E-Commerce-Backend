<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SpecificController;
use App\Http\Controllers\TagController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/currencies', [CurrencyController::class, 'index']);

Route::get('/home/tags', [TagController::class, 'homeTagsProducts']);
Route::get('/tags', [TagController::class, 'index']);
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products/search', [ProductController::class, 'search']);
Route::get('/search/filters', [SpecificController::class, 'searchFilters']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/products/{product}/specifics', [ProductController::class, 'specifics']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/roots', [CategoryController::class, 'rootCategories']);
Route::get('/categories/sections', [CategoryController::class, 'sectionCategories']);
Route::get('/categories/items', [CategoryController::class, 'itemCategories']);



Route::middleware('auth:sanctum')->group(function () {

    /*
     * for admin
     */
    {
        Route::middleware('is_admin')->group(function () {

            Route::prefix('/products')->group(function () {
                Route::post('/create', [ProductController::class, 'store']);

                Route::prefix('/{product}')->group(function () {
                    Route::put('/specifics/update', [ProductController::class, 'updateSpecifics']);
                    Route::post('/update', [ProductController::class, 'update']);
                    Route::delete('/images/delete/{image}', [ProductController::class, 'deleteImage']);
                    Route::delete('/delete', [ProductController::class, 'destroy']);
                });
            });

            Route::get('/orders', [OrderController::class, 'index']);
            Route::post('/categories/create', [CategoryController::class, 'store']);
            Route::post('/brands/create', [BrandController::class, 'store']);
            Route::post('/tags/create', [TagController::class, 'store']);
            Route::get('/orders/all', [OrderController::class, 'index']);
            Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);
            Route::post('/currencies/create', [CurrencyController::class, 'store']);
            Route::put('/currencies/{currency}/update', [CurrencyController::class, 'update']);
            Route::delete('/currencies/{currency}/delete', [CurrencyController::class, 'destroy']);

        });
    }

    /*
     * for all logged in users
     */
    {
        Route::get('/cart', [CartController::class, 'index']);
        Route::get('/orders/my-orders', [OrderController::class, 'userOrders']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::get('/user/orders', [OrderController::class, 'userOrders']);
        Route::post('/order/checkout', [OrderController::class, 'store']);
        Route::delete('/cart/{specific}/delete', [CartController::class, 'deleteSpecific']);
        Route::post('/add-to-cart/{specific}', [CartController::class, 'store']);
    }

});
