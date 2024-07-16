<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BakedGoodsController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\IngredientController;

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

Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout'])->name('logout');
Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'profile'])->name('profile');

Route::post('/register', [UserController::class, 'registerUser'])->name('auth.register');
Route::post('/login', [UserController::class, 'loginUser'])->name('auth.login');
Route::put('/api/baked_goods/images/{imageId}/set-thumbnail', [BakedGoodsController::class, 'setThumbnail']);
Route::delete('/baked_goods/images/{id}', [BakedGoodsController::class, 'deleteImage']);

Route::apiResource('ingredients', IngredientController::class);
Route::apiResource('baked_goods', BakedGoodsController::class);
Route::apiResource('discounts', DiscountController::class);
