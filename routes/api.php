<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

// Product update route
Route::post('/product-update', [ProductController::class, 'update']);

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);

Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index'); // Public product listing
});

// Authenticated routes with Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Products: add/store for logged-in users
    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::post('/', 'store');
    });

    // Orders: add order route
    Route::post('/orders', [OrderController::class, 'store']);

    // Get current logged-in user info
    Route::get('/user', function () {
        return response()->json(Auth::user());
    });

    // Admin-only route
    Route::middleware('role:admin')->get('/api/admin/dashboard', function () {
        return response()->json([
            'message' => 'Hello Admin',
            'user' => Auth::user()
        ]);
    });

    // Vendor-only route
    Route::middleware('role:vendor')->get('/api/vendor/dashboard', function () {
        return response()->json([
            'message' => 'Hello Vendor',
            'user' => Auth::user()
        ]);
    });

    // User-only route
    Route::middleware('role:user')->get('/api/user/dashboard', function () {
        return response()->json([
            'message' => 'Hello User',
            'user' => Auth::user()
        ]);
    });
});
