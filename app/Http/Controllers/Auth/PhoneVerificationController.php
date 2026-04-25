<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\PhoneVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PhoneVerificationController extends Controller
{
    public function __construct(protected PhoneVerification $phoneVerification)
    {
    }

    public function create(Request $request): RedirectResponse|View
    {
        if (! $request->user()->requiresPhoneVerification() || $request->user()->hasVerifiedPhone()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return view('auth.verify-phone', [
            'phone' => $request->user()->phone,
            'phone_display' => $request->user()->phone,
            'debug_code' => app()->isLocal() || config('app.debug')
                ? $this->phoneVerification->currentCode($request->user())
                : null,
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        if (! $request->user()->requiresPhoneVerification()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $this->phoneVerification->resend($request->user());

        return back()->with('status', 'phone-verification-code-sent');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        if (! $request->user()->requiresPhoneVerification()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if (! $this->phoneVerification->verify($request->user(), $request->string('code')->toString())) {
            throw ValidationException::withMessages([
                'code' => 'رمز التحقق غير صحيح أو منتهي الصلاحية. أعد إرسال رمز جديد ثم حاول مرة أخرى.',
            ]);
        }

        return redirect()->intended(route('dashboard', absolute: false).'?phone_verified=1');
    }
}