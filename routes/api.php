<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorProductController;
use Illuminate\Support\Facades\Route;

// Public Routes with Throttling
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// Authenticated User Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/info', [AuthController::class, 'user_info']); // Fetch authenticated user's details
    Route::get('/roles', [RoleController::class, 'index']);

    // User-Specific Routes
    Route::get('/user/orders', [OrderController::class, 'userOrders']);  // Fetch user orders
    Route::get('/user/wishlist', [UserController::class, 'getWishlist']); // Wishlist items
    Route::delete('/user/wishlist/{id}', [UserController::class, 'removeFromWishlist']); // Remove item

    // Admin-only Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/admin/register', [AdminController::class, 'adminRegister']);
        Route::get('/admin/pending-users', [AdminController::class, 'pendingApprovals']);
        Route::put('/admin/approve-user/{id}', [AdminController::class, 'approveUser']);
        Route::put('/admin/assign-role/{id}', [AdminController::class, 'assignRole']);
        Route::put('/admin/suspend-user/{id}', [UserController::class, 'suspendUser']);

        // Admin User Management
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/user/{id}', [UserController::class, 'show']);
        Route::post('/user', [UserController::class, 'store']);
        Route::put('/user/{id}', [UserController::class, 'update']);
        Route::delete('/user/{id}', [UserController::class, 'destroy']);

        // Admin-only Role Management
        Route::post('/roles', [RoleController::class, 'store']);
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    });

    // Vendor-specific Routes
    Route::middleware(['role:vendor'])->group(function () {
        // Vendor Product Management
        Route::get('/vendor/products', [VendorProductController::class, 'index']);
        Route::post('/vendor/products', [VendorProductController::class, 'store']);
        Route::put('/vendor/products/{id}', [VendorProductController::class, 'update']);
        Route::delete('/vendor/products/{id}', [VendorProductController::class, 'destroy']);

        // Order Management
        Route::get('/vendor/orders', [VendorController::class, 'getOrders']);
        Route::put('/vendor/orders/{id}/status', [VendorController::class, 'updateOrderStatus']);

        // Earnings & Reports
        Route::get('/vendor/earnings', [VendorController::class, 'getEarnings']);

        // Store Settings
        Route::get('/vendor/store', [VendorController::class, 'getStoreDetails']);
        Route::put('/vendor/store', [VendorController::class, 'updateStoreDetails']);
    });
});
