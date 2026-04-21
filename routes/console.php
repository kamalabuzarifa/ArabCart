<?php

use App\Support\SocialProviders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('social:status', function () {
    $mockEnabled = app()->environment('local') && (bool) config('app.social_login_mock', false);

    $this->info('Local Mock Mode: '.($mockEnabled ? 'ON' : 'OFF'));

    $rows = [];

    foreach (SocialProviders::all() as $provider) {
        $missing = SocialProviders::missingKeys($provider);

        $rows[] = [
            SocialProviders::label($provider),
            SocialProviders::isConfigured($provider) ? 'READY' : 'MISSING',
            empty($missing) ? '-' : implode(', ', $missing),
        ];
    }

    $this->table(['Provider', 'Status', 'Missing'], $rows);
})->purpose('Show social login configuration status');
