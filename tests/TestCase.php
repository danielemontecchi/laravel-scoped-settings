<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use DanieleMontecchi\LaravelScopedSettings\LaravelScopedSettingsServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelScopedSettingsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:nHhJhC0gIOE8LGkPvw0qKfQfsGKKMTx3pxRNPq2ZJ0g=');
        $app['config']->set('database.default', 'testing');

        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../tests/database/migrations');
    }
}
