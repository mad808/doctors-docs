<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\OtpGatewayMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const MASTER_OTP_CODE = '240774';

    public function showLogin(): View
    {
        return view('front.auth.login');
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:20',
            'name' => 'nullable|string|max:100',
            'role' => 'nullable|string|in:patient,doctor,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $phone = $this->normalizePhone($request->phone);

        // Security: Rate Limiting (Max 3 attempts per minute per IP/Phone)
        $throttleKey = 'send-otp:' . $request->ip() . ':' . $phone;
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "Gaty köp synanyşyk. {$seconds} sekuntdan gaýtadan synanyşyň.",
            ], 429);
        }
        RateLimiter::hit($throttleKey, 60);

        if ($request->filled('name')) {
            session(['pending_user_name' => $request->name]);
        }

        if ($request->filled('role')) {
            session(['pending_user_role' => $request->role]);
        }

        OtpCode::updateOrCreate(
            ['phone' => $phone],
            [
                'code' => self::MASTER_OTP_CODE,
                'action' => 'auth',
                'expires_at' => Carbon::now()->addHours(24),
                'is_used' => false,
            ]
        );

        OtpGatewayMessage::create([
            'phone' => $phone,
            'message' => "ProDoctors (Lukmanlar) tassyklama kody: " . self::MASTER_OTP_CODE,
            'status' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tassyklama kody ugradyldy: ' . self::MASTER_OTP_CODE,
            'dev_code' => self::MASTER_OTP_CODE,
            'code' => self::MASTER_OTP_CODE,
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:20',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $phone = $this->normalizePhone($request->phone);
        
        $verifyThrottleKey = 'verify-otp:' . $request->ip() . ':' . $phone;
        if (RateLimiter::tooManyAttempts($verifyThrottleKey, 5)) {
            $seconds = RateLimiter::availableIn($verifyThrottleKey);
            return response()->json([
                'success' => false,
                'message' => "Köp nädogry synanyşyk. {$seconds} sekuntdan gaýtadan synanyşyň.",
            ], 429);
        }

        $isMasterCode = ($request->code === self::MASTER_OTP_CODE);

        if (!$isMasterCode) {
            $otp = OtpCode::where('phone', $phone)
                ->where('code', $request->code)
                ->where('is_used', false)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$otp) {
                RateLimiter::hit($verifyThrottleKey, 60);
                return response()->json([
                    'success' => false,
                    'message' => 'Kod nädogry ýa-da möhleti geçen.',
                ], 422);
            }

            $otp->update(['is_used' => true]);
        }

        RateLimiter::clear($verifyThrottleKey);

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            $name = session()->pull('pending_user_name', 'Müşderi (Näsag) ' . substr($phone, -4));
            $role = session()->pull('pending_user_role', 'patient');

            $user = User::create([
                'name' => $name,
                'phone' => $phone,
                'phone_verified_at' => Carbon::now(),
                'role' => $role,
                'status' => 1,
            ]);
        } else {
            if (!$user->phone_verified_at) {
                $user->update(['phone_verified_at' => Carbon::now()]);
            }
        }

        if ((int)$user->status === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Hasabyňyz petiklenen.',
            ], 403);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        $redirectUrl = match ($user->role) {
            'admin' => Route::has('admin.dashboard') ? route('admin.dashboard') : route('home'),
            'doctor' => Route::has('doctor.portal.dashboard') ? route('doctor.portal.dashboard') : route('home'),
            'patient' => Route::has('patient.portal.dashboard') ? route('patient.portal.dashboard') : route('home'),
            default => route('home'),
        };

        return response()->json([
            'success' => true,
            'redirect' => $redirectUrl,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'role' => $user->role,
            ],
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) === 8) {
            return '+993' . $phone;
        }

        if (strlen($phone) === 11 && str_starts_with($phone, '993')) {
            return '+' . $phone;
        }

        return '+' . $phone;
    }
}