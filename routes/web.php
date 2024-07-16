<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BakedGoodsController;
use App\Http\Controllers\IngredientController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/register', [UserController::class, 'register'])->name('page.register');
Route::get('/login', [UserController::class, 'index'])->name('page.login');
Route::get('/forgot', [UserController::class, 'forgot'])->name('page.forgot');
Route::get('/reset', [UserController::class, 'reset'])->name('page.reset');

// Route::post('/register-user', [UserController::class, 'registerUser'])->name('auth.register');
// Route::post('/login-user', [UserController::class, 'loginUser'])->name('auth.login');



Route::group(['middleware' => ['LoginCheck']], function (){
    // Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/', [UserController::class, 'index']);
    Route::get('/logout', [UserController::class, 'logout'])->name('auth.logout');
});

// Route::get('/ingredient', [IngredientController::class, 'ingredient']);
// Route::post('/store', [IngredientController::class, 'store'])->name('store');
// Route::get('/fetchAll', [IngredientController::class, 'fetchAll'])->name('fetchAll');
// Route::delete('/delete', [IngredientController::class, 'delete'])->name('delete');
// Route::get('/edit', [IngredientController::class, 'edit'])->name('edit');
// Route::post('/update', [IngredientController::class, 'update'])->name('update');

Route::put('/api/baked_goods/images/{imageId}/set-thumbnail', [BakedGoodsController::class, 'setThumbnail']);


Route::view('/ingredient-all', 'ingredients.index');
Route::view('/discount-all', 'discounts.index');
Route::view('/bakedgood-all', 'baked_goods.index');
