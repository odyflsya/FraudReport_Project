<?php

namespace App\Services;

use App\Mail\SendOtpCode;
use App\Models\EmailOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function generateAndStore(string $email): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::create([
            'email' => $email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        return $code;
    }

    public function invalidateUnused(string $email): void
    {
        EmailOtp::where('email', $email)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);
    }

    public function send(string $email, string $code): bool
    {
        try {
            Mail::to($email)->send(new SendOtpCode($code));

            if (config('mail.default') === 'log') {
                Log::info("[OTP] Kode verifikasi untuk {$email}: {$code}");
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim OTP', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            if (app()->environment('local')) {
                Log::info("[OTP FALLBACK] Kode verifikasi untuk {$email}: {$code}");

                return true;
            }

            return false;
        }
    }

    public function issue(string $email): array
    {
        $code = $this->generateAndStore($email);
        $sent = $this->send($email, $code);

        return compact('code', 'sent');
    }

    public function reissue(string $email): array
    {
        $this->invalidateUnused($email);

        return $this->issue($email);
    }

    public function shouldShowDevCode(): bool
    {
        return app()->environment('local') && config('mail.default') === 'log';
    }
}
