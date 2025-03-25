<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;

// 🌍 Home Page Route
Route::get('/', function () {
    return view('welcome');
});

// 🛡 Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class,   'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// 🔐 Protected Routes (Only Authenticated Users)
Route::middleware(['auth'])->group(function () {

    // 👤 User Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // 🛒 User Orders
    Route::get('/orders', [UserController::class, 'getOrders']);

    // 🏪 Vendor Dashboard
    Route::middleware(['role:vendor'])->group(function () {
        Route::get('/vendor/dashboard', [VendorController::class, 'dashboard']);
        Route::get('/vendor/products', [VendorController::class, 'getProducts']);
        Route::post('/vendor/products', [VendorController::class, 'createProduct']);
        Route::put('/vendor/products/{id}', [VendorController::class, 'updateProduct']);
        Route::delete('/vendor/products/{id}', [VendorController::class, 'deleteProduct']);
        Route::get('/vendor/orders', [VendorController::class, 'getOrders']);
        Route::post('/vendor/orders/{id}/update', [VendorController::class, 'updateOrderStatus']);
        Route::get('/vendor/earnings', [VendorController::class, 'getEarnings']);
    });

    // 🛠 Admin Dashboard
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/admin/vendors', [AdminController::class, 'listVendors']);
        Route::get('/admin/users', [AdminController::class, 'listUsers']);
    });
});
