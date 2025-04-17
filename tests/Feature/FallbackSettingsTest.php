<?php

use Tests\Stubs\TestUser;
use DanieleMontecchi\LaravelScopedSettings\Facades\Setting;

beforeEach(function () {
    config(['scoped-settings.fallback_to_global' => true]);
});

it('returns scoped value when present', function () {
    $user = new TestUser(['id' => 1]);

    Setting::for($user)->set('ui.theme', 'dark');

    expect(Setting::for($user)->get('ui.theme'))->toBe('dark');
});

it('returns default and stores it when scoped value is missing and default is provided', function () {
    $user = new TestUser(['id' => 1]);

    expect(Setting::for($user)->get('ui.layout', 'grid'))->toBe('grid');

    // Value should now exist in DB
    expect(Setting::for($user)->get('ui.layout'))->toBe('grid');
});

it('returns global value if scoped is missing and no default is provided', function () {
    $user = new TestUser(['id' => 1]);

    Setting::forGlobal()->set('ui.language', 'en');

    expect(Setting::for($user)->get('ui.language'))->toBe('en');
});

it('stores global fallback into scoped settings when used', function () {
    $user = new TestUser(['id' => 1]);

    Setting::forGlobal()->set('ui.timezone', 'UTC');

    Setting::for($user)->get('ui.timezone');

    // Now the scoped value should exist
    expect(Setting::for($user)->get('ui.timezone'))->toBe('UTC');
});

it('returns null if no value is found and fallback is disabled', function () {
    config(['scoped-settings.fallback_to_global' => false]);

    $user = new TestUser(['id' => 1]);

    expect(Setting::for($user)->get('nonexistent'))->toBeNull();
});
