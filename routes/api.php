<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/brands', [BrandController::class, 'index']);
Route::post('/brands/create', [BrandController::class, 'store']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products/create', [ProductController::class, 'store']);
Route::post('/products/{product}/add-images', [ProductController::class, 'addImages']);
Route::delete('/products/{product}/delete', [ProductController::class, 'destroy']);
Route::post('/categories/create', [CategoryController::class, 'store']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/roots', [CategoryController::class, 'rootCategories']);
Route::get('/categories/sections', [CategoryController::class, 'sectionCategories']);
Route::get('/categories/items', [CategoryController::class, 'itemCategories']);
Route::get('/orders', [OrderController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
