<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class SocialAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.google.client_id' => 'test-google-client-id',
            'services.google.client_secret' => 'test-google-client-secret',
            'services.google.redirect' => 'http://localhost:8000/auth/google/callback',
        ]);
    }

    public function test_user_is_redirected_to_social_provider(): void
    {
        Socialite::fake('google');

        $response = $this->get(route('social.redirect', ['provider' => 'google']));

        $response->assertRedirect();
    }

    public function test_user_can_login_with_social_provider_callback(): void
    {
        Socialite::fake('google', (new SocialiteUser)->map([
            'id' => 'google-123',
            'name' => 'Social User',
            'email' => 'social@example.com',
            'avatar' => 'https://example.com/avatar.png',
        ]));

        $response = $this->get(route('social.callback', ['provider' => 'google']));

        $this->assertAuthenticated();
        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'name' => 'Social User',
            'email' => 'social@example.com',
            'provider' => 'google',
            'provider_id' => 'google-123',
        ]);
    }

    public function test_social_provider_callback_shows_provider_error_message(): void
    {
        $response = $this->get(route('social.callback', [
            'provider' => 'facebook',
            'error' => 'access_denied',
            'error_reason' => 'user_denied',
            'error_description' => 'The user denied your request.',
        ]));
        

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('social_error', function (string $message) {
            return str_contains($message, 'The user denied your request.');
        });
    }
}
