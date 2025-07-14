<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
public function apiLogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    return response()->json([
        'message' => 'Invalid credentials'
    ], 401);
}

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);
        }

        // Fallback: Web login
        return match ($user->role) {
            'admin' => redirect('/admin/dashboard'),
            'vendor' => redirect('/vendor/dashboard'),
            default => redirect('/dashboard'),
        };
    }

    // Fail case
    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    throw ValidationException::withMessages([
        'email' => ['The provided credentials do not match our records.'],
    ]);
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}