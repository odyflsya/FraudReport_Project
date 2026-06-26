<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpCode;
use App\Models\EmailOtp;
use App\Models\User;
use App\Models\UserActivity;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'pending',
        ]);

        UserActivity::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'activity' => 'Register',
            'module' => 'Auth',
            'description' => 'User registered, awaiting email verification and admin approval',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $result = $this->otpService->issue($user->email);

        session([
            'otp_email' => $user->email,
            'otp_purpose' => 'register',
        ]);

        if ($this->otpService->shouldShowDevCode()) {
            session(['dev_otp_code' => $result['code']]);
        }

        $message = $result['sent']
            ? 'Akun berhasil dibuat. Cek email Anda untuk kode verifikasi (berlaku 10 menit).'
            : 'Akun berhasil dibuat, namun email gagal dikirim. Silakan klik "Minta kode baru".';

        if ($this->otpService->shouldShowDevCode()) {
            $message .= ' Mode development: kode juga tercatat di storage/logs/laravel.log.';
        }

        return redirect()->route('verification.code')->with('status', $message);
    }
}
