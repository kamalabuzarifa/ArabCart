<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force Socialite to use the SocialiteProviders LinkedIn provider (OpenID-based).
        Event::listen(function (SocialiteWasCalled $event): void {
            $event->extendSocialite('linkedin', \SocialiteProviders\LinkedIn\Provider::class);
        });
    }
}
