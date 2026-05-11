<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpCode;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpLoginController extends Controller
{
    public function showRequestForm(): View
    {
        return view('auth.otp-login');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email belum terdaftar. Silakan daftar terlebih dahulu.'])->withInput();
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::create([
            'email' => $user->email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new SendOtpCode($code));

        session(['otp_email' => $user->email]);

        return redirect()->route('verification.code')->with('status', 'Kode verifikasi telah dikirim ke email Anda.');
    }

    public function showVerifyForm(Request $request): View
    {
        return view('auth.otp-verify', [
            'email' => $request->session()->get('otp_email'),
        ]);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $email = $request->session()->get('otp_email');

        if (! $email) {
            return redirect()->route('verification.code')->withErrors(['email' => 'Sesi verifikasi telah berakhir. Silakan mulai ulang proses login.']);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('verification.code')->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Invalidate kode OTP lama yang belum digunakan
        EmailOtp::where('email', $email)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Generate kode baru
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::create([
            'email' => $user->email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new SendOtpCode($code));

        return redirect()->route('verification.code')->with('status', 'Kode verifikasi baru telah dikirim ke email Anda.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $otp = EmailOtp::where('email', $request->email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otp || ! Hash::check($request->code, $otp->code_hash)) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid atau sudah kadaluarsa.'])->withInput();
        }

        $otp->markUsed();

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        $user->markEmailAsVerified();

        Auth::login($user);
        $request->session()->forget('otp_email');

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
