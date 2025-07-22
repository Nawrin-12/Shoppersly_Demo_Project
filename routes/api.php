<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register',[AuthController::class,'register']);

Route::post('/forget-password',[AuthController::class,'forgetPassword']);
//Route::get('/reset-password/{token}',[AuthController::class,'resetPassword']);
