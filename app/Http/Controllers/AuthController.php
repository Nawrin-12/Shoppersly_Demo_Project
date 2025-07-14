<?php

namespace App\Http\Controllers;

use App\Http\Requests\forgetPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\ForgetPassword;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try{
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'number' => $validated['number'],
                'password' => Hash::make($validated['password'])
            ]);
            return response()->json([
                'message' => "Registration done Successfully",
                'user' => $user,
            ]);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json([
                'message' => "Registration failed. Please try again",
//                'error' => $exception->getMessage()
            ]);
        }

    }
    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse{
        $validated = $request->validated();
        try{
            $user=User::query()->where('email',$validated['email'])->first();
            if(!$user){
                return response()->json([
                    'message' => "User does not exist",
                ]);
            }
            $token = Str::random(20);
            DB::table('password_reset_tokens')->UpdateOrInsert(
                ['email' => $validated['email']],
                [
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
            Mail::to($validated['email'])->send(new ResetPasswordMail($token,$validated['email']));
            return response()->json([
                'message' => "Password reset link has been sent to your email",
            ]);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json([
                'message' => "Failed to send mail",
                'error' => $exception->getMessage(),
            ]);
        }
    }

//    public function forgetPassword(forgetPasswordRequest $request): JsonResponse
//    {
//        $validated = $request->validated();
//        try {
//            $user = User::firstWhere('email', $validated['email']);
//            if (!$user) {
//                return response()->json([
//                    'message' => "User does not exist",
//                ]);
//            }
//
//            $token = str::random(20);
//            DB::table('password_reset_tokens')->insert([
//                'email' => $request->email,
//                'token' => $token,
//                'created_at' => Carbon::now()
//            ]);
//            Mail::to($token)->send(
//                new ForgetPassword($token)
//            );
//            return response()->json([
//                'message' => "Reset token has been sent your email",
//            ]);
//        } catch (\Exception $exception) {
//            Log::error($exception->getMessage());
//            return response()->json([
//                'message' => "Failed to send email",
//                'error' => $exception->getMessage(),
//            ]);
//        }
//    }



}
