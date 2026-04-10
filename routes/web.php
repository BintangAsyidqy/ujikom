<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    // Admin Routes
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->middleware('role:admin')->name('admin.dashboard');
    Route::get('/admin/categories', [AuthController::class, 'adminCategories'])->middleware('role:admin')->name('admin.categories');
    Route::post('/admin/categories', [AuthController::class, 'storeCategory'])->middleware('role:admin')->name('admin.categories.add');
    Route::get('/admin/categories/{id}/edit', [AuthController::class, 'editCategory'])->middleware('role:admin')->name('admin.categories.edit');
    Route::put('/admin/categories/{id}', [AuthController::class, 'updateCategory'])->middleware('role:admin')->name('admin.categories.update');
    // Operator Routes
    Route::get('/operator/dashboard', [AuthController::class, 'dashboard'])->middleware('role:operator')->name('operator.dashboard');
    Route::get('/error', [AuthController::class, 'errorImage'])->name('error.image');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});