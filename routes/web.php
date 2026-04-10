<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\UserController;
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
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Category Routes
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.add');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // Item Routes
        Route::get('/items', [ItemController::class, 'index'])->name('admin.items');
        Route::post('/items', [ItemController::class, 'store'])->name('admin.items.add');
        Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('admin.items.edit');
        Route::put('/items/{item}', [ItemController::class, 'update'])->name('admin.items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('admin.items.destroy');
        Route::get('/items/export', [ItemController::class, 'export'])->name('admin.items.export');

        // User Management Routes
        Route::get('/users/admin', [UserController::class, 'adminIndex'])->name('admin.users.admin');
        Route::get('/users/admin/export', [UserController::class, 'export'])->defaults('role', 'admin')->name('admin.users.admin.export');
        Route::get('/users/operator', [UserController::class, 'operatorIndex'])->name('admin.users.operator');
        Route::get('/users/operator/export', [UserController::class, 'export'])->defaults('role', 'operator')->name('admin.users.operator.export');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        

    });

    // Operator Routes
    Route::get('/operator/dashboard', [AuthController::class, 'dashboard'])->middleware('role:operator')->name('operator.dashboard');
    Route::middleware('role:operator')->prefix('operator')->group(function () {
        Route::get('/items', function () {
            $items = \App\Models\Item::with('category')->get()->map(function ($item) {
                $item->lending_total = \App\Models\Lending::where('returned', false)
                    ->get()
                    ->sum(function ($lending) use ($item) {
                        foreach ($lending->items as $li) {
                            if ($li['item_id'] == $item->id) return $li['total'];
                        }
                        return 0;
                    });
                return $item;
            });
            return view('operator.items', compact('items'));
        })->name('operator.items');
        Route::get('/lending', [LendingController::class, 'index'])->name('operator.lending');
        Route::post('/lending', [LendingController::class, 'store'])->name('operator.lending.store');
        Route::post('/lending/{lending}/returned', [LendingController::class, 'returned'])->name('operator.lending.returned');
        Route::delete('/lending/{lending}', [LendingController::class, 'destroy'])->name('operator.lending.destroy');
        Route::get('/lending/export', [LendingController::class, 'export'])->name('operator.lending.export');
        Route::get('/users', [UserController::class, 'operatorSelf'])->name('operator.users');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('operator.users.update');
    });
    Route::get('/error', [AuthController::class, 'errorImage'])->name('error.image');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});