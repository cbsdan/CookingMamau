<?php

use App\Models\BakedGood;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableController;
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
Auth::routes();

Route::view('/', 'welcome')->name('welcome');

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware(['auth']);

//Acount
Route::middleware(['auth'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('user.update.profile');
    Route::put('/profile/password/update', [ProfileController::class, 'updatePassword'])->name('user.update.password');
});

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::put('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.update.profile');
    Route::put('/admin/password/update', [AdminController::class, 'updatePassword'])->name('admin.update.password');
});

// Users
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [UserController::class, 'profile'])->name('profile');
//     Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');
// });
// Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');

Route::get('/user/index',[DatatableController::class, 'userIndex'])->name('user.index');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
});

// Route::get('/register', [UserController::class, 'register'])->name('page.register');
// Route::get('/login', [UserController::class, 'index'])->name('page.login');
// Route::get('/forgot', [UserController::class, 'forgot'])->name('page.forgot');
// Route::get('/reset', [UserController::class, 'reset'])->name('page.reset');



Route::put('/api/baked_goods/images/{imageId}/set-thumbnail', [BakedGoodsController::class, 'setThumbnail']);


//crud
Route::middleware(['admin'])->group(function () {
    Route::view('/ingredient-all', 'ingredients.index')->name('ingredients');
    Route::view('/discount-all', 'discounts.index')->name('discounts');
    Route::view('/bakedgood-all', 'baked_goods.index')->name('bakedgoods');
    Route::view('/available_schedules-all', 'available_schedules.index')->name('available_schedules');
});

