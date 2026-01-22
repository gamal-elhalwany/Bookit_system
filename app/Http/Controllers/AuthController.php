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
class AuthController extends Controller
{
    /**
     * Login the user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not found or wrong email', // الخطأ هنا محدد للإيميل
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password',
            ], 401);
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
        // التحقق من البيانات
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // توليد كود تحقق عشوائي
        $verificationCode = rand(100000, 999999);

        // حفظ الكود في جدول users
        $user->verification_code = $verificationCode;
        $user->save();

        // إرسال الكود بالإيميل
        Mail::to($user->email)->send(new VerifyEmail($user, $verificationCode));
        // الرد بدون توكن (لأن الحساب لسه مش متحقق)
        return response()->json([
            'status' => 'success',
            'message' => 'Account created. Please check your email for the verification code.',
            'user' => $user,
        ], 201);
    }
    public function verify(Request $request)
    {
        // التحقق من البيانات اللي جاية من الفرونت
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string'
        ]);

        // البحث عن المستخدم بالكود والإيميل
        $user = User::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid verification code'
            ], 400);
        }

        // تحديث حالة التحقق
        $user->email_verified_at = now();
        $user->verification_code = null; // نمسح الكود بعد الاستخدام
        $user->save();

        // إنشاء التوكن بعد التحقق
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $otp = rand(100000, 999999); // كود 6 أرقام
        $expiresAt = now()->addMinutes(10); // صالح لمدة 10 دقائق

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

    public function resetWithOtp(Request $request)
    {

        DB::table('password_otps')
            ->where('expires_at', '<', now())
            ->delete();

        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:8|confirmed',
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

        // امسح الـ OTP بعد الاستخدام
        DB::table('password_otps')->where('id', $record->id)->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }


}
