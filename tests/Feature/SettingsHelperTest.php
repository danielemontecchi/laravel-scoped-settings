<?php

use DanieleMontecchi\LaravelScopedSettings\Managers\SettingsManager;
use Tests\Stubs\TestUser;

it('can resolve settings manager from helper', function () {
    expect(setting())->toBeInstanceOf(SettingsManager::class);
});

it('can access global setting via helper', function () {
    setting()->set('site.name', 'DanieleMontecchi');
    expect(setting()->get('site.name'))->toBe('DanieleMontecchi');
});

it('can use helper with model scope', function () {
    $user = TestUser::create(['id' => 1, 'name' => 'Jack']);

    setting()->for($user)->set('preferences.theme', 'dark');

    expect(setting()->for($user)->get('preferences.theme'))->toBe('dark');
});
