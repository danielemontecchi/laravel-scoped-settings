<?php

namespace DanieleMontecchi\LaravelScopedSettings;

use DanieleMontecchi\LaravelScopedSettings\Commands\ClearSettingsCommand;
use DanieleMontecchi\LaravelScopedSettings\Commands\DumpSettingsCommand;
use DanieleMontecchi\LaravelScopedSettings\Commands\ListSettingsCommand;
use DanieleMontecchi\LaravelScopedSettings\Managers\SettingsManager;
use Illuminate\Support\ServiceProvider;

class LaravelScopedSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-scoped-settings.php' => config_path('laravel-scoped-settings.php'),
        ], 'config');

        $this->loadMigrationsFrom([
            __DIR__ . '/../database/migrations',
            __DIR__ . '/../tests/database/migrations',
        ]);

        // Carica helper se esiste
        if (file_exists(__DIR__ . '/Support/helpers.php')) {
            require_once __DIR__ . '/Support/helpers.php';
        }
    }

    public function register(): void
    {
        $this->app->singleton('laravel-scoped-settings', fn() => new SettingsManager());

        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-scoped-settings.php',
            'laravel-scoped-settings'
        );

        $this->commands([
            ListSettingsCommand::class,
            ClearSettingsCommand::class,
            DumpSettingsCommand::class,
        ]);
    }
}
