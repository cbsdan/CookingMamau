<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\AvailableScheduleController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

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


//Users
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});




// Discounts 
Route::get('discounts', [DiscountController::class, 'index'])->name('discounts.index');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('discounts/create', [DiscountController::class, 'create'])->name('discounts.create');
    Route::post('discounts', [DiscountController::class, 'store'])->name('discounts.store');
    Route::get('discounts/{discount}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
    Route::put('discounts/{discount}', [DiscountController::class, 'update'])->name('discounts.update');
    Route::delete('discounts/{discount}', [DiscountController::class, 'destroy'])->name('discounts.destroy');
});



// Available Schedule
Route::get('available_schedules', [AvailableScheduleController::class, 'index'])->name('available_schedules.index');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('available_schedules/create', [AvailableScheduleController::class, 'create'])->name('available_schedules.create');
    Route::post('available_schedules', [AvailableScheduleController::class, 'store'])->name('available_schedules.store');
    Route::get('available_schedules/{available_schedule}/edit', [AvailableScheduleController::class, 'edit'])->name('available_schedules.edit');
    Route::put('available_schedules/{available_schedule}', [AvailableScheduleController::class, 'update'])->name('available_schedules.update');
    Route::delete('available_schedules/{available_schedule}', [AvailableScheduleController::class, 'destroy'])->name('available_schedules.destroy');
});

