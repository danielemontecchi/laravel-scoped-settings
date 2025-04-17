<?php

namespace DanieleMontecchi\LaravelScopedSettings;

use DanieleMontecchi\LaravelScopedSettings\Commands\ClearSettingsCommand;
use DanieleMontecchi\LaravelScopedSettings\Commands\ExportSettingsCommand;
use DanieleMontecchi\LaravelScopedSettings\Commands\ListSettingsCommand;
use DanieleMontecchi\LaravelScopedSettings\Commands\ImportSettingsCommand;
use DanieleMontecchi\LaravelScopedSettings\Managers\SettingsManager;
use Illuminate\Support\ServiceProvider;

class LaravelScopedSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Config
        $this->publishes([
            __DIR__ . '/../config/laravel-scoped-settings.php' => config_path('laravel-scoped-settings.php'),
        ], 'config');

        // Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'laravel-scoped-settings-migrations');

        $this->loadMigrationsFrom([
            __DIR__ . '/../database/migrations',
            __DIR__ . '/../tests/database/migrations',
        ]);

        // Helpers
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
            ExportSettingsCommand::class,
            ImportSettingsCommand::class,
            ClearSettingsCommand::class,
        ]);
    }
}
