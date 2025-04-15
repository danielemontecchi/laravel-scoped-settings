<?php

use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\artisan;

beforeEach(function () {
    if (!Schema::hasTable('settings')) {
        artisan('migrate', ['--path' => 'vendor/danielemontecchi/laravel-scoped-settings/database/migrations']);
    }
});

it('stores and retrieves a scalar value correctly', function () {
    $setting = Setting::create([
        'group' => 'test',
        'key' => 'string_key',
        'value' => 'hello world',
    ]);

    expect($setting->value)->toBe('hello world');
});

it('stores and retrieves an array value correctly', function () {
    $array = ['foo' => 'bar', 'baz' => [1, 2, 3]];

    $setting = Setting::create([
        'group' => 'test',
        'key' => 'array_key',
        'value' => $array,
    ]);

    expect($setting->value)
        ->toBeArray()
        ->toMatchArray($array);
});

it('stores and retrieves a boolean correctly', function () {
    $setting = Setting::create([
        'group' => 'test',
        'key' => 'boolean_key',
        'value' => true,
    ]);

    expect($setting->value)->toBeTrue();
});

it('stores and retrieves a number correctly', function () {
    $setting = Setting::create([
        'group' => 'test',
        'key' => 'int_key',
        'value' => 42,
    ]);

    expect($setting->value)->toBe(42);
});
