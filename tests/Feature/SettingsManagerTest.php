<?php

use DanieleMontecchi\LaravelScopedSettings\Facades\Setting;
use DanieleMontecchi\LaravelScopedSettings\Models\Setting as SettingModel;
use Tests\Stubs\TestUser;

it('can set and get a global setting', function () {
    Setting::set('timezone', 'UTC');

    expect(Setting::get('timezone'))->toBe('UTC');
});

it('can set and get a setting scoped to a user', function () {
    $user = TestUser::create(['id' => 1, 'name' => 'Jack']);

    Setting::for($user)->set('timezone', 'Europe/Rome');

    expect(Setting::for($user)->get('timezone'))->toBe('Europe/Rome');
});

it('does not leak scoped values across users', function () {
    $user1 = TestUser::create(['id' => 1, 'name' => 'Jack']);
    Setting::for($user1)->set('timezone', 'Europe/Rome');
    expect(Setting::for($user1)->get('timezone'))->toBe('Europe/Rome');

    $user2 = TestUser::create(['id' => 2, 'name' => 'John']);
    Setting::for($user2)->set('timezone', 'Europe/London');
    expect(Setting::for($user2)->get('timezone'))->toBe('Europe/London');
});

it('can forget a scoped setting', function () {
    $user = TestUser::create(['id' => 1, 'name' => 'Jack']);

    Setting::for($user)->set('timezone', 'Europe/Rome');
    expect(Setting::for($user)->get('timezone'))->toBe('Europe/Rome');

    Setting::for($user)->forget('timezone');
    expect(Setting::for($user)->get('timezone'))->toBeNull();
});

it('can check if a setting exists', function () {
    Setting::set('feature.enabled', true);

    expect(Setting::has('feature.enabled'))->toBeTrue()
        ->and(Setting::has('nonexistent'))->toBeFalse();
});

it('can retrieve all settings as flat keys', function () {
    Setting::set('timezone', 'UTC');
    Setting::set('locale', 'en');

    expect(Setting::all())->toEqual([
        'default.timezone' => 'UTC',
        'default.locale' => 'en',
    ]);
});

it('can retrieve a group of settings scoped to a model', function () {
    $user = TestUser::create(['id' => 1, 'name' => 'Jack']);

    Setting::for($user)->set('timezone', 'Europe/Rome');
    Setting::for($user)->set('currency', 'EUR');
    dump(Setting::for($user)->all());

    expect(Setting::for($user)->all())->toEqual([
        'default.timezone' => 'Europe/Rome',
        'default.currency' => 'EUR',
    ]);
});

it('can clear scope and retrieve global settings', function () {
    $user = TestUser::create(['id' => 1, 'name' => 'Daniele']);

    Setting::for($user)->set('timezone', 'Europe/Rome');
    Setting::forGlobal()->set('timezone', 'UTC');

    expect(Setting::for($user)->get('timezone'))->toBe('Europe/Rome')
        ->and(Setting::forGlobal()->get('timezone'))->toBe('UTC');
});

it('uses global settings when no scope is defined', function () {
    Setting::forGlobal()->set('currency', 'USD');

    expect(Setting::get('currency'))->toBe('USD');
});

it('can use scoping with anonymous model', function () {
    $model = new class {
        public function getKey()
        {
            return 999;
        }
    };

    Setting::for($model)->set('color', 'blue');

    expect(Setting::for($model)->get('color'))->toBe('blue');
});

it('stores same key with different values per scope', function () {
    $user1 = TestUser::create(['id' => 1, 'name' => 'Alice']);
    $user2 = TestUser::create(['id' => 2, 'name' => 'Bob']);

    Setting::for($user1)->set('theme', 'light');
    Setting::for($user2)->set('theme', 'dark');
    Setting::forGlobal()->set('theme', 'system');

    expect(Setting::for($user1)->get('theme'))->toBe('light')
        ->and(Setting::for($user2)->get('theme'))->toBe('dark')
        ->and(Setting::forGlobal()->get('theme'))->toBe('system');
});