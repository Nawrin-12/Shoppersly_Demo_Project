<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'number' => $validated['number'],
                'password' => Hash::make($validated['password']),
            ]);
            return response()->json([
                'success' => true,
                'message' => "Registration done successfully",
                'user' => $user,
            ], 201);
        } catch (\Exception $exception) {
            Log::error('Registration failed: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Registration failed. Please try again",
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            // Validate input
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Find user by email
            $user = User::where('email', $credentials['email'])->first();

            // Check if user exists and password is correct
            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Create Sanctum token
            $token = $user->createToken('api-token')->plainTextToken;

            // Return response
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'token' => $token,
            ]);
        } catch (\Exception $exception) {
            Log::error('Login failed: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Login failed. Please try again.',
            ], 500);
        }
    }

    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => "User does not exist",
                ], 404);
            }

            $token = Str::random(20);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $validated['email']],
                [
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );

            Mail::to($validated['email'])->send(new ResetPasswordMail($token, $validated['email']));
            
            return response()->json([
                'success' => true,
                'message' => "Password reset link has been sent to your email",
            ]);
        } catch (\Exception $exception) {
            Log::error('Password reset failed: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Failed to send reset email",
            ], 500);
        }
    }

      public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Logout from all devices (delete all tokens)
     */
    public function logoutAllDevices(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Delete all tokens for the user
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out from all devices successfully.'
            ]);
        } catch (\Exception $exception) {
            Log::error('Logout all devices failed: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout from all devices',
            ], 500);
        }
    }
}