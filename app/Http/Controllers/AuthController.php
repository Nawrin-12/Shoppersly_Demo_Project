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
                'message' => "Registration done Successfully",
                'user' => $user,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json([
                'message' => "Registration failed. Please try again",
            ]);
        }
    }


public function login(Request $request): JsonResponse
{
    // Validate input
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Find user by email
    $user = User::where('email', $credentials['email'])->first();

    // Check if user exists and password is correct
    if (! $user || ! is_string($user->password) || ! Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Login unsuccessful'], 401);
    }

    // Create Sanctum token
    $token = $user->createToken('api-token')->plainTextToken;

    // Return response
    return response()->json([
        'message' => 'Login successful',
        'user' => [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'token' => $token,
    ]);
}



    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json([
                    'message' => "User does not exist",
                ]);
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
                'message' => "Password reset link has been sent to your email",
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json([
                'message' => "Failed to send mail",
                'error' => $exception->getMessage(),
            ]);
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

    public function singleLogout(Request $request): JsonResponse{
        $user = Auth()->user();
        if (!$user) {
            return response()->json([
                'message' => "User does not exist",
            ]);
        }
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out from current device successfully',
        ]);
    }
}
