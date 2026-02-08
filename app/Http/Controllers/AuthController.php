<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Login the user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not found or wrong email',
            ], 404);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password',
            ], 401);
        }

        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Please verify your email first'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Register a new user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $verificationCode = rand(100000, 999999);

        $user->verification_code = $verificationCode;
        $user->save();

        Mail::to($user->email)->send(new VerifyEmail($user, $verificationCode));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Account created. Please check your email for the verification code.',
            'user' => $user,
        ], 201);
    }

    /**
     * Verify user's email
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string'
        ]);

        $user = User::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid verification credentials'
            ], 400);
        }

        $user->email_verified_at = now();
        $user->verification_code = null; // نمسح الكود بعد الاستخدام
        $user->save();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Send OTP to email for password reset
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        DB::table('password_otps')->where('email', $request->email)->delete();

        DB::table('password_otps')->insert([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json(['message' => 'OTP sent to your email']);
    }

    /**
     * Reset password using OTP
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPasswordWithOtp(Request $request)
    {

        DB::table('password_otps')
            ->where('expires_at', '<', now())
            ->delete();

        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    // ->letters()
                    // ->numbers(),
            ],
        ]);

        $record = DB::table('password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = bcrypt($request->password);
        $user->save();
        
        DB::table('password_otps')->where('id', $record->id)->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }
}
