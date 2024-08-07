<?php

use App\Models\BakedGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiscountController;
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

//Welcome and Home page
Route::view('/', 'welcome')->name('welcome');

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware(['auth:sanctum']);


//User Acount
Route::middleware(['auth'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('user.update.profile');
    Route::put('/profile/password/update', [ProfileController::class, 'updatePassword'])->name('user.update.password');
});


Route::get('/user/index',[DatatableController::class, 'userIndex'])->name('user.index');

Route::middleware(['admin'])->group(function () {
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/admin/profile', 'admin.profile')->name('admin.profile');
});

Route::put('/api/baked_goods/images/{imageId}/set-thumbnail', [BakedGoodsController::class, 'setThumbnail']);

//Discount
Route::view('/discounts', 'discounts')->name('discounts_page');
//crud
Route::middleware(['auth', 'admin'])->group(function () {
    Route::view('/ingredient-all', 'ingredients.index')->name('ingredients');
    Route::view('/discount-all', 'discounts.index')->name('discounts');
    Route::view('/bakedgood-all', 'baked_goods.index')->name('bakedgoods');
    Route::view('/available_schedules-all', 'available_schedules.index')->name('available_schedules');
    Route::view('/users-all', 'admin.users.index')->name('users');
    Route::view('/orders-all', 'order.index')->name('orders');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/checkout', 'order.checkout')->name('checkout_page');
    Route::view('/my-orders', 'user.orders')->name('my-orders');
});

Route::post('/save-token', function (Request $request) {
    $request->session()->put('token', $request->token);
    return response()->json(['message' => 'Token saved to session']);
});

Route::get('/get-token', function (Request $request) {
    return response()->json(['token' => $request->session()->get('token')]);
});


// Users
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [UserController::class, 'profile'])->name('profile');
//     Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');
// });
// Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');


// Route::get('/register', [UserController::class, 'register'])->name('page.register');
// Route::get('/login', [UserController::class, 'index'])->name('page.login');
// Route::get('/forgot', [UserController::class, 'forgot'])->name('page.forgot');
// Route::get('/reset', [UserController::class, 'reset'])->name('page.reset');
