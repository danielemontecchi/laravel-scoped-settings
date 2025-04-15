<?php

use DanieleMontecchi\LaravelScopedSettings\Managers\SettingsManager;

it('can resolve the settings manager and call a test method', function () {
    $manager = app(SettingsManager::class);

    expect($manager)
        ->toBeInstanceOf(SettingsManager::class)
        ->and($manager->test())->toBe('This is ok');
});
