<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/product-update', [ProductController::class, 'update']);
Route::delete('/product-delete', [ProductController::class, 'delete']);
Route::get('/product-delete', [ProductController::class, 'SoftDeletedData']);
