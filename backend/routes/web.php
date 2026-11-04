<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SubCategoryController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/categories');

// Login / logout
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});
Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Admin panel to manage the mega menu (login required)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('sub-categories', SubCategoryController::class)->except('show');
    Route::resource('posts', PostController::class)->except('show');
});
