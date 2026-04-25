<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Support\PhoneVerification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $socialSignup = $request->session()->get('social_signup');

        $countryCode = $this->normalizePhone($request->string('phone_country_code', '+970')->toString());
        $phoneNumber = $this->normalizePhone($request->string('phone')->toString());

        if (! str_starts_with($countryCode, '+')) {
            $countryCode = '+'.ltrim($countryCode, '+');
        }

        $phoneNumber = ltrim($phoneNumber, '+');
        $phoneNumber = ltrim($phoneNumber, '0');

        $normalizedPhone = $this->normalizePhone($countryCode.$phoneNumber);
        $request->merge([
            'phone_country_code' => $countryCode,
            'phone' => $normalizedPhone,
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_country_code' => ['required', 'string', 'regex:/^\+[1-9]\d{0,3}$/'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{7,14}$/', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone_country_code.regex' => 'مفتاح الدولة غير صحيح. أدخل الصيغة مثل +970 أو +966.',
            'phone.regex' => 'صيغة رقم الهاتف غير صحيحة. أدخل الرقم بصيغة دولية مثل +9665XXXXXXXX.',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $normalizedPhone,
            'password' => Hash::make($request->password),
        ];

        if (
            is_array($socialSignup)
            && strcasecmp((string) data_get($socialSignup, 'email'), (string) $request->email) === 0
        ) {
            $userData['provider'] = (string) data_get($socialSignup, 'provider');
            $userData['provider_id'] = (string) data_get($socialSignup, 'provider_id');
            $userData['avatar'] = data_get($socialSignup, 'avatar');
            $userData['email_verified_at'] = now();
        }

        $user = User::create($userData);
        app(PhoneVerification::class)->issue($user);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->forget('social_signup');

        return redirect()->intended(route('dashboard', absolute: false));
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
}
