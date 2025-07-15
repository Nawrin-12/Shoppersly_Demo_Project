<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
Route::get('/products', [ProductController::class, 'index']);
// Authenticated routes
Route::middleware('auth')->group(function () {

    // Get current logged-in user
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
