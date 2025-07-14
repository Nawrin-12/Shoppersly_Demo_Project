<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->get('/user', function () {
    return response()->json(Auth::user());
});

//  Admin-only route
Route::middleware(['auth', 'role:admin'])->get('/api/admin/dashboard', function () {
    return response()->json([
        'message' => 'Hello Admin',
        'user' => Auth::user()
    ]);
});

// Vendor-only route
Route::middleware(['auth', 'role:vendor'])->get('/api/vendor/dashboard', function () {
    return response()->json([
        'message' => 'Hello Vendor',
        'user' => Auth::user()
    ]);
});

// User-only route
Route::middleware(['auth', 'role:user'])->get('/api/user/dashboard', function () {
    return response()->json([
        'message' => 'Hello User',
        'user' => Auth::user()
    ]);
});
