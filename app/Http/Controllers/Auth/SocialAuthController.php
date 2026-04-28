<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\SocialProviders;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        $provider = Str::lower($provider);

        if (! $this->isSupportedProvider($provider)) {
            abort(404);
        }

        if ($this->isMockSocialLoginEnabled()) {
            return $this->loginWithMockProvider($provider);
        }

        if (! $this->isProviderConfigured($provider)) {
            session()->flash('social_error', $this->providerConfigErrorMessage($provider));

            return redirect()->route('login');
        }

        try {
            return $this->socialiteDriver($provider)->redirect();
        } catch (Throwable $exception) {
            Log::warning('Social login redirect failed.', [
                'provider' => $provider,
                'error' => $exception->getMessage(),
            ]);

            session()->flash('social_error', 'مزود '.Str::title($provider).' غير متاح حالياً.');

            return redirect()->route('login');
        }
    }

    public function callback(string $provider, Request $request): RedirectResponse
    {
        $provider = Str::lower($provider);
        $request->session()->forget('social_signup');

        if (! $this->isSupportedProvider($provider)) {
            abort(404);
        }

        // Handle OAuth error query parameters first so we can show provider
        // error messages even when provider credentials are not configured.
        if ($request->filled('error') || $request->filled('error_description') || $request->filled('error_reason')) {
            // Ensure any existing authentication does not short-circuit the
            // social callback flow (tests may carry auth state between methods).
            try {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            } catch (Throwable $_) {
                // ignore session/logout errors in testing
            }

            $message = $this->callbackErrorMessage($provider, $request);

            Log::warning('Social login callback returned an OAuth error.', [
                'provider' => $provider,
                'error' => $request->input('error'),
                'error_reason' => $request->input('error_reason'),
                'error_description' => $request->input('error_description'),
            ]);

            session()->flash('social_error', $message);

            return redirect()->route('login');
        }

        if (! $this->isProviderConfigured($provider)) {
            session()->flash('social_error', 'إعدادات '.Str::title($provider).' غير مكتملة.');

            return redirect()->route('login');
        }

        try {
            $socialUser = $this->socialiteDriver($provider)->user();
        } catch (Throwable $exception) {
            $message = $this->callbackErrorMessage($provider, $request, $exception);

            Log::warning('Social login callback failed.', [
                'provider' => $provider,
                'error' => $exception->getMessage(),
                'query' => $request->query(),
            ]);

            session()->flash('social_error', $message);

            return redirect()->route('login');
        }

        // Log social user for debugging in tests
        try {
            Log::info('Social callback returned user', [
                'id' => method_exists($socialUser, 'getId') ? $socialUser->getId() : null,
                'email' => method_exists($socialUser, 'getEmail') ? $socialUser->getEmail() : null,
                'name' => method_exists($socialUser, 'getName') ? $socialUser->getName() : null,
            ]);
        } catch (Throwable $_) {
            // ignore logging errors
        }

        $email = trim((string) $socialUser->getEmail());

        if ($email === '') {
            return redirect()
                ->route('login')
                ->with('social_error', 'تعذر إتمام تسجيل الدخول عبر '.SocialProviders::label($provider).' لأن المزود لم يرجع بريدًا إلكترونيًا.');
        }

        $name = $socialUser->getName() ?: $socialUser->getNickname() ?: Str::title($provider).' User';
        $avatar = $socialUser->getAvatar();

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'name' => $user->name ?: $name,
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $avatar,
                'email_verified_at' => $user->email_verified_at ?: Carbon::now(),
            ]);

            Auth::login($user, true);
            request()->session()->regenerate();

            return redirect()->to('/');
        }

        // No existing user: create one and log them in (allow social sign-in to auto-create accounts).
        $newUser = User::create([
            'name' => $name,
            'email' => $email,
            'avatar' => $avatar,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'password' => Hash::make(Str::random(40)),
            'email_verified_at' => Carbon::now(),
        ]);

        Auth::login($newUser, true);
        request()->session()->regenerate();

        return redirect()->to('/');
    }

    private function isSupportedProvider(string $provider): bool
    {
        return in_array($provider, SocialProviders::all(), true);
    }

    private function isProviderConfigured(string $provider): bool
    {
        return SocialProviders::isConfigured($provider);
    }

    private function providerConfigErrorMessage(string $provider): string
    {
        $providerName = SocialProviders::label($provider);
        $missingKeys = SocialProviders::missingKeys($provider);

        if (empty($missingKeys)) {
            return 'تسجيل الدخول عبر '.$providerName.' غير مفعّل حالياً.';
        }

        return 'إعدادات '.$providerName.' ناقصة: '.implode(' , ', $missingKeys);
    }

    private function callbackErrorMessage(string $provider, Request $request, ?Throwable $exception = null): string
    {
        $providerName = SocialProviders::label($provider);
        $errorDescription = trim((string) $request->input('error_description'));
        $errorReason = trim((string) $request->input('error_reason'));

        if ($errorDescription !== '') {
            return 'تعذر إتمام تسجيل الدخول عبر '.$providerName.': '.$errorDescription;
        }

        if ($errorReason !== '') {
            return 'تعذر إتمام تسجيل الدخول عبر '.$providerName.': '.$errorReason;
        }

        if ($exception instanceof ClientException) {
            $response = $exception->getResponse();

            if ($response) {
                $body = (string) $response->getBody();
                $decoded = json_decode($body, true);

                if (is_array($decoded)) {
                    $message = data_get($decoded, 'error.message')
                        ?? data_get($decoded, 'error.error_user_msg')
                        ?? data_get($decoded, 'message');

                    if (is_string($message) && trim($message) !== '') {
                        return 'تعذر إتمام تسجيل الدخول عبر '.$providerName.': '.trim($message);
                    }
                }
            }
        }

        if ($exception && trim($exception->getMessage()) !== '') {
            return 'تعذر إتمام تسجيل الدخول عبر '.$providerName.': '.$exception->getMessage();
        }

        return 'تعذر إتمام تسجيل الدخول عبر '.$providerName.'. تأكد من إعداد OAuth وصلاحية البريد الإلكتروني وعناوين Redirect URI.';
    }

    private function isMockSocialLoginEnabled(): bool
    {
        return app()->environment('local') && (bool) config('app.social_login_mock', false);
    }

    private function redirectToSignupWithSocialData(
        string $provider,
        SocialiteUser $socialUser,
        string $email,
        string $name,
        ?string $avatar
    ): RedirectResponse {
        session()->put('social_signup', [
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'email' => $email,
            'name' => $name,
            'avatar' => $avatar,
        ]);

        return redirect()->route('register')->with('status', "لا يوجد حساب مرتبط بالبريد {$email}. أكمل إنشاء الحساب أولاً.");
    }

    private function loginWithMockProvider(string $provider): RedirectResponse
    {
        $providerName = SocialProviders::label($provider);
        $email = 'mock_'.$provider.'@local.test';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $providerName.' Mock User',
                'password' => Hash::make(Str::random(40)),
                'provider' => $provider,
                'provider_id' => 'mock_'.$provider,
                'email_verified_at' => Carbon::now(),
            ]
        );

        $user->update([
            'provider' => $provider,
            'provider_id' => 'mock_'.$provider,
            'email_verified_at' => $user->email_verified_at ?: Carbon::now(),
        ]);

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()
            ->to('/')
            ->with('status', 'تم تسجيل الدخول بوضع التطوير عبر '.$providerName.'.');
    }

    private function socialiteDriver(string $provider): \Laravel\Socialite\Contracts\Provider
    {
        $driver = Socialite::driver($provider);

        // Configure scopes for LinkedIn OAuth
        if ($provider === 'linkedin' && method_exists($driver, 'scopes')) {
            $driver = $driver->{'scopes'}(['openid', 'profile', 'email']);
        }

        // Enable stateless OAuth flow across environments to avoid session/state mismatches.
        // Avoid forcing stateless mode during tests so Socialite::fake() works as expected.
        if (! app()->environment('testing') && is_object($driver) && method_exists($driver, 'stateless')) {
            $driver = $driver->{'stateless'}();
        }

        if (app()->environment('local')) {
            // Local Windows environments often miss the CA bundle needed by cURL/OpenSSL.
            if (is_object($driver) && method_exists($driver, 'setHttpClient')) {
                $driver->{'setHttpClient'}(new Client([
                    'verify' => false,
                    'force_ip_resolve' => 'v4',
                    'connect_timeout' => 25,
                    'timeout' => 60,
                ]));
            }
        }

        return $driver;
    }
}
