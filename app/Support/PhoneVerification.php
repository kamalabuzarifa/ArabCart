<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class PhoneVerification
{
    public function issue(User $user): void
    {
        if (blank($user->phone)) {
            throw new RuntimeException('رقم الهاتف غير متوفر لهذا الحساب.');
        }

        $to = $this->normalizePhone((string) $user->phone);

        if (! $this->isE164($to)) {
            throw new RuntimeException('رقم الهاتف يجب أن يكون بصيغة دولية E.164 مثل +9665XXXXXXXX.');
        }

        Cache::put($this->cacheKey($user), $this->devCode(), now()->addMinutes($this->ttlMinutes()));
    }

    public function verify(User $user, string $code): bool
    {
        $expectedCode = Cache::get($this->cacheKey($user));
        if (blank($expectedCode) || (string) $code !== (string) $expectedCode) {
            return false;
        }

        Cache::forget($this->cacheKey($user));
        $user->markPhoneAsVerified();

        return true;
    }

    public function resend(User $user): void
    {
        $this->issue($user);
    }

    public function currentCode(User $user): ?string
    {
        $code = Cache::get($this->cacheKey($user));

        return filled($code) ? (string) $code : null;
    }

    protected function cacheKey(User $user): string
    {
        return 'phone_verification:code:'.$user->getKey();
    }

    protected function devCode(): string
    {
        return (string) config('services.phone_verification.dev_code', '708946');
    }

    protected function ttlMinutes(): int
    {
        return (int) config('services.phone_verification.ttl_minutes', 10);
    }

    protected function normalizePhone(string $phone): string
    {
        $normalized = str_replace(
            ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $phone,
        );

        return preg_replace('/[^\d+]/', '', $normalized) ?? $normalized;
    }

    protected function isE164(string $phone): bool
    {
        return preg_match('/^\+[1-9]\d{7,14}$/', $phone) === 1;
    }
}