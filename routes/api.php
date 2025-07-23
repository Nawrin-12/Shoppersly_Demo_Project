<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\ProductController;
Route::post('/product-update', [ProductController::class, 'update']);

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
Route::middleware('auth:sanctum')->get('/orders', [OrderController::class, 'index']);


// Public product listing
Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
});

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    // Product creation and update (only logged in users)
    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::post('/', 'store');
        Route::post('/update', 'update');
    });


    // Add order route
    Route::post('/orders', [OrderController::class, 'store']);


    Route::post('/orders', [OrderController::class, 'store']);
    // Get current logged-in user info
    Route::get('/user', function () {
        return response()->json(Auth::user());
    });

    // Role-based dashboards
    Route::middleware('role:admin')->get('/api/admin/dashboard', function () {
        return response()->json([
            'message' => 'Hello Admin',
            'user' => Auth::user()
        ]);
    });

    Route::middleware('role:vendor')->get('/api/vendor/dashboard', function () {
        return response()->json([
            'message' => 'Hello Vendor',
            'user' => Auth::user()
        ]);
    });

    Route::middleware('role:user')->get('/api/user/dashboard', function () {
        return response()->json([
            'message' => 'Hello User',
            'user' => Auth::user()
        ]);
    });
});
});
