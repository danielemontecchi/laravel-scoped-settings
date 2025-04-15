<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use DanieleMontecchi\LaravelScopedSettings\LaravelScopedSettingsServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelScopedSettingsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        // Se serve puoi configurare il config di default, DB in memory, ecc.
        $app['config']->set('app.key', 'base64:nHhJhC0gIOE8LGkPvw0qKfQfsGKKMTx3pxRNPq2ZJ0g=');
    }
}
