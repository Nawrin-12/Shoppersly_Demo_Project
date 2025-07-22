<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Test route for email template (remove in production)
Route::get('test', function () {
    // Generate a sample token for testing
    $token = 'sample-reset-token-12345';
    $email = 'test@example.com';
    
    return new \App\Mail\ResetPasswordMail($token, $email);
});