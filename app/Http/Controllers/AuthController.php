<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Jobs\PasswordReset;
use App\Mail\ForgetPassword;
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
                    'created_at' => Carbon::now()
                ]);
            $resetUrl = config('api/reset-password?token='.$token .'&email=' .urlencode($user->email));
            PasswordReset::dispatch($user,$token,$resetUrl);
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

    public function resetPassword(resetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try{
            $user=User::query()->where('email',$validated['email'])->first();
            if(!$user){
                return response()->json([
                    'message' => "User does not exist",
                ]);
            }

            $reset = DB::table('password_reset_tokens')
                ->where('email',$validated['email'])
                ->where('token',$validated['token'])
                ->first();

            if(!$reset ||Carbon::parse($reset->created_at)->addMinutes(60)->isPast()){
                return response()->json([
                    'message' => "Invalid token or expired",
                ]);
            }
            $user->update(['password' => Hash::make($validated['password'])]);
            DB::table('password_reset_tokens')->where('email',$validated['email'])->delete();
            return response()->json([
                'message' => "Your password has been changed",
            ]);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json([
                'message' => "Failed to send mail",
                'error' => $exception->getMessage(),
            ]);
        }

    }

}
