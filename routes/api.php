<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\BakedGoodsController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\AvailableScheduleController;

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


// Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'profile']);
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/profile', [UserController::class, 'profile']);
//     Route::post('/admin/action', [DashboardController::class, 'adminAction'])->name('admin.action');
//     Route::post('/logout', [UserController::class, 'logout'])->name('logout');
// });

//Authentication
Route::post('/register', [UserController::class, 'registerUser'])->name('auth.register');
Route::post('/login', [UserController::class, 'loginUser'])->name('auth.login');
Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'profile']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

Route::apiResource('/order', OrderController::class);
Route::apiResource('available_schedules', AvailableScheduleController::class);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('ingredients', IngredientController::class);
    Route::apiResource('discounts', DiscountController::class);
    Route::apiResource('baked_goods', BakedGoodsController::class);

    Route::middleware(['auth.admin'])->group(function () {
        Route::get('/dashboard/userInfo', [DashboardController::class, 'usersInfo']);
        Route::get('/dashboard/salesStats', [DashboardController::class, 'salesStats']);
        Route::get('/dashboard/salesEarnings', [DashboardController::class, 'salesEarnings']);
        Route::get('/dashboard/topBakedGoods', [DashboardController::class, 'topBakedGoods']);
        Route::get('/dashboard/latestSevenScheduleSales', [DashboardController::class, 'latestSevenScheduleSales']);
        Route::get('/dashboard/previousOrder', [DashboardController::class, 'previousOrder']);
    });
});


// Route::apiResource('ingredients', IngredientController::class);
// Route::apiResource('discounts', DiscountController::class);
// Route::apiResource('available_schedules', AvailableScheduleController::class);
// Route::apiResource('baked_goods', BakedGoodsController::class);
// Route::apiResource('/order', OrderController::class);

//Order Other route
Route::get('order/{id}/user', [OrderController::class, 'userOrder']);
Route::patch('order/{id}/status', [OrderController::class, 'updateStatus']);
Route::get('order/{id}/status_counts', [OrderController::class, 'statusCounts']);

Route::get('bakedgoods/paginate', [BakedGoodsController::class, 'bakedGoodPaginate']);
Route::delete('/baked_goods/images/{id}', [BakedGoodsController::class, 'deleteImage']);

//excel imports
Route::post('bakedgood/import', [BakedGoodsController::class,'bakedgoodsImport']);
Route::post('ingredient/import', [IngredientController::class,'ingredientImport']);
Route::post('discount/import', [DiscountController::class, 'discountImport']);

//User
Route::get('/user/fetch', [DatatableController::class, 'fetchUser'])->name('user.fetch');
Route::get('/user/{id}', [UserController::class, 'getUserById']);
Route::put('/user/{id}', [UserController::class, 'updateUser']);

//Cart
Route::post('/cart/add', [CartController::class, 'addToCart']);
Route::post('/cart/remove', [CartController::class, 'removeFromCart']);
Route::post('/cart/update', [CartController::class, 'updateCart']);
Route::get('/cart/items', [CartController::class, 'fetchCartItems']);
Route::delete('/cart/items/delete', [CartController::class, 'destory']);

//Checkout
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/check-discount', [DiscountController::class, 'checkDiscount'])->name('check.discount');

//Account
Route::apiResource('/users', UserController::class);
Route::put('/users/{id}/update-role', [UserController::class, 'updateRole']);
Route::put('/users/{id}/update-status', [UserController::class, 'updateStatus']);
Route::get('/users/{id}/getProfileImagePath', [UserController::class, 'getProfileImagePath']);

//Admin
Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.update.profile');
Route::post('/admin/password/update', [AdminController::class, 'updatePassword'])->name('admin.update.password');

//Meili Search
Route::get('/search/bakedgoods', [SearchController::class, 'search']);

//Dashboard
// Route::get('/dashboard/userInfo', [DashboardController::class, 'usersInfo']);
// Route::get('/dashboard/salesStats', [DashboardController::class, 'salesStats']);
// Route::get('/dashboard/salesEarnings', [DashboardController::class, 'salesEarnings']);
// Route::get('/dashboard/topBakedGoods', [DashboardController::class, 'topBakedGoods']);
// Route::get('/dashboard/latestSevenScheduleSales', [DashboardController::class, 'latestSevenScheduleSales']);
// Route::get('/dashboard/previousOrder', [DashboardController::class, 'previousOrder']);
