<?php

//use Illuminate\Support\Facades\Artisan;
//use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\Storage;
use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
//use function Pest\Laravel\assertDatabaseMissing;
//use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $settings = [
        ['group' => 'app', 'key' => 'name', 'value' => '"Test App"'],
        ['group' => 'app', 'key' => 'debug', 'value' => 'true'],
        ['group' => 'ui', 'key' => 'theme', 'value' => '"dark"'],
        ['group' => 'user', 'key' => 'notifications', 'value' => 'true', 'scope_type' => '\\App\\Models\\User', 'scope_id' => 1],
    ];
    $settings = array_map(function ($setting) {
        return array_merge([
            'scope_type' => null,
            'scope_id' => null,
        ], $setting);
    }, $settings);
    Setting::insert($settings);
});

it('lists all settings', function () {
    $this->artisan('settings:list')
//        ->expectsTable(
//            ['Group', 'Key', 'Value'],
//            [
//                ['app', 'name', '"Test App"'],
//                ['app', 'debug', 'true'],
//                ['ui', 'theme', '"dark"'],
//                ['user', 'notifications', 'true'],
//            ]
//        )
        ->assertExitCode(0);
});

it('clears all settings', function () {
    $this->artisan('settings:clear')
        ->expectsOutput('Deleted 4 setting(s).')
        ->assertExitCode(0);

    expect(Setting::count())->toBe(0);
});

it('exports all settings', function () {
    $this->artisan('settings:export')
        ->expectsOutputToContain('Settings exported successfully')
        ->assertExitCode(0);
});

it('exports only global settings with --only-global', function () {
    $this->artisan('settings:export --only-global')
        ->expectsOutputToContain('Settings exported successfully')
        ->assertExitCode(0);
});

it('exports settings by scope type and ID', function () {
    $this->artisan('settings:export --scope-type=User --scope-id=1')
        ->expectsOutputToContain('Settings exported successfully')
        ->assertExitCode(0);
});

//it('imports settings using --merge (default behavior)', function () {
//    $exportPath = storage_path('app/settings/test_merge.json');
//    File::put($exportPath, json_encode([
//        ['group' => 'app', 'key' => 'name', 'value' => '"New App"'],
//        ['group' => 'ui', 'key' => 'theme', 'value' => '"light"'],
//    ]));
//
//    $this->artisan("settings:import {$exportPath} --merge")
//        ->assertExitCode(0);
//
//    expect(Setting::count())->toBeGreaterThanOrEqual(4);
//    assertDatabaseHas('settings', ['group' => 'app', 'key' => 'name', 'value' => '"New App"']);
//    assertDatabaseHas('settings', ['group' => 'ui', 'key' => 'theme', 'value' => '"light"']);
//});
//
//it('imports settings using --overwrite (clears all first)', function () {
//    $exportPath = storage_path('app/settings/test_overwrite.json');
//    File::put($exportPath, json_encode([
//        ['group' => 'custom', 'key' => 'key', 'value' => '"overwritten"'],
//    ]));
//
//    $this->artisan("settings:import {$exportPath} --overwrite")
//        ->assertExitCode(0);
//
//    expect(Setting::count())->toBe(1);
//    assertDatabaseHas('settings', ['group' => 'custom', 'key' => 'key', 'value' => '"overwritten"']);
//});

it('fails to import from non-existing file', function () {
    $this->artisan('settings:import notfound.json')
        ->expectsOutputToContain('does not exist.')
        ->assertExitCode(1);
});
