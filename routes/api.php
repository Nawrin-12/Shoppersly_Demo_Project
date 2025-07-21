<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
Route::post('/product-update', [ProductController::class, 'update']);

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
});
// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    // only logged in users can add
    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::post('/', 'store');  
    });

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

Route::post('/product-update', [ProductController::class, 'update']);
Route::delete('/product-delete', [ProductController::class, 'delete']);
Route::get('/product-delete', [ProductController::class, 'SoftDeletedData']);

Route::get('/product-details/{id}', [ProductController::class, 'ProductDetails']);
