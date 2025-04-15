<?php

namespace DanieleMontecchi\LaravelScopedSettings;

use DanieleMontecchi\LaravelScopedSettings\Managers\SettingsManager;
use Illuminate\Support\ServiceProvider;

class LaravelScopedSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-scoped-settings.php' => config_path('laravel-scoped-settings.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->app->singleton('laravel-scoped-settings', function ($app) {
            return new SettingsManager($app);
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-scoped-settings.php',
            'laravel-scoped-settings'
        );
    }
}
