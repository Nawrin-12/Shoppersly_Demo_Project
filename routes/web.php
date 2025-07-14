<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - require authentication
Route::middleware('auth')->group(function () {

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        });
    });

    // Vendor routes
    Route::middleware('role:vendor')->group(function () {
        Route::get('/vendor/dashboard', function () {
            return view('vendor.dashboard');
        });
    });

    // User routes
    Route::middleware('role:user')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        });
    });
});
=======

Route::get('/', function () {
    return view('welcome');
});
>>>>>>> 52bac08 (registration_api_function)
