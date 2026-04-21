<?php

namespace App\Support;

class SocialProviders
{
    /**
     * @return array<int, string>
     */
    public static function all(): array
    {
        return ['google', 'linkedin', 'facebook'];
    }

    public static function label(string $provider): string
    {
        return match ($provider) {
            'google' => 'Google',
            'linkedin' => 'LinkedIn',
            'facebook' => 'Facebook',
            default => ucfirst($provider),
        };
    }

    public static function isConfigured(string $provider): bool
    {
        if (! in_array($provider, self::all(), true)) {
            return false;
        }

        return filled(config('services.'.$provider.'.client_id'))
            && filled(config('services.'.$provider.'.client_secret'))
            && filled(config('services.'.$provider.'.redirect'));
    }

    /**
     * @return array<int, string>
     */
    public static function missingKeys(string $provider): array
    {
        $missingKeys = [];

        if ($provider === 'google') {
            if (blank(config('services.google.client_id'))) {
                $missingKeys[] = 'GOOGLE_CLIENT_ID';
            }

            if (blank(config('services.google.client_secret'))) {
                $missingKeys[] = 'GOOGLE_CLIENT_SECRET';
            }

            if (blank(config('services.google.redirect'))) {
                $missingKeys[] = 'GOOGLE_REDIRECT_URI';
            }

            return $missingKeys;
        }

        if ($provider === 'facebook') {
            if (blank(config('services.facebook.client_id'))) {
                $missingKeys[] = 'FACEBOOK_CLIENT_ID';
            }

            if (blank(config('services.facebook.client_secret'))) {
                $missingKeys[] = 'FACEBOOK_CLIENT_SECRET';
            }

            if (blank(config('services.facebook.redirect'))) {
                $missingKeys[] = 'FACEBOOK_REDIRECT_URI';
            }

            return $missingKeys;
        }

        if ($provider !== 'linkedin') {
            return $missingKeys;
        }

        if (blank(config('services.linkedin.client_id'))) {
            $missingKeys[] = 'LINKEDIN_CLIENT_ID';
        }

        if (blank(config('services.linkedin.client_secret'))) {
            $missingKeys[] = 'LINKEDIN_CLIENT_SECRET';
        }

        if (blank(config('services.linkedin.redirect'))) {
            $missingKeys[] = 'LINKEDIN_REDIRECT_URI';
        }

        return $missingKeys;
    }
}
