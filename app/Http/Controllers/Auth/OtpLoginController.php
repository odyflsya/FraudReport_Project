<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\User;
use App\Models\UserActivity;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class OtpLoginController extends Controller
{
    public function __construct(private OtpService $otpService) {}

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

        $result = $this->otpService->issue($user->email);

        session([
            'otp_email' => $user->email,
            'otp_purpose' => 'login',
        ]);

        if ($this->otpService->shouldShowDevCode()) {
            session(['dev_otp_code' => $result['code']]);
        }

        $message = $result['sent']
            ? 'Kode verifikasi telah dikirim ke email Anda.'
            : 'Gagal mengirim email. Silakan coba lagi.';

        return redirect()->route('verification.code')->with('status', $message);
    }

    public function showVerifyForm(Request $request): View
    {
        return view('auth.otp-verify', [
            'email' => $request->session()->get('otp_email'),
            'purpose' => $request->session()->get('otp_purpose', 'register'),
            'showDevCode' => $this->otpService->shouldShowDevCode(),
            'devOtpCode' => $request->session()->get('dev_otp_code'),
        ]);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $email = $request->session()->get('otp_email');

        if (! $email) {
            return redirect()->route('verification.code')->withErrors([
                'email' => 'Sesi verifikasi telah berakhir. Silakan daftar atau login ulang.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('verification.code')->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $result = $this->otpService->reissue($user->email);

        if ($this->otpService->shouldShowDevCode()) {
            session(['dev_otp_code' => $result['code']]);
        }

        $message = $result['sent']
            ? 'Kode verifikasi baru telah dikirim ke email Anda.'
            : 'Gagal mengirim email. Silakan coba lagi.';

        if ($this->otpService->shouldShowDevCode()) {
            $message .= ' Mode development: cek storage/logs/laravel.log.';
        }

        return redirect()->route('verification.code')->with('status', $message);
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

        UserActivity::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'activity' => 'Verify Email',
            'module' => 'Auth',
            'description' => 'Email verified via OTP',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->session()->forget(['otp_email', 'otp_purpose', 'dev_otp_code']);

        // Belum disetujui admin → tunggu approval, jangan login
        if ($user->status !== 'active') {
            return redirect()->route('registration.pending')->with(
                'status',
                'Email berhasil diverifikasi. Akun Anda menunggu persetujuan administrator sebelum dapat login.'
            );
        }

        Auth::login($user);
        $request->session()->regenerate();

        $redirectRoute = $user->isAdmin()
            ? route('admin.users.index', absolute: false)
            : route('dashboard', absolute: false);

        return redirect()->intended($redirectRoute);
    }
}
