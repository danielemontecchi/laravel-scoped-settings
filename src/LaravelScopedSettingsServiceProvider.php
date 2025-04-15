<?php

namespace DanieleMontecchi\LaravelScopedSettings;

use Illuminate\Support\ServiceProvider;

class LaravelScopedSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Pubblica il file di configurazione
        $this->publishes([
            __DIR__ . '/../config/laravel-scoped-settings.php' => config_path('laravel-scoped-settings.php'),
        ], 'config');
    }

    public function register(): void
    {
        // Unisci la configurazione del pacchetto con quella dell'app
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-scoped-settings.php',
            'laravel-scoped-settings'
        );
    }
}
