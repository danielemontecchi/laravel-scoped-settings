<?php

use DanieleMontecchi\LaravelScopedSettings\Models\Setting;

it('lists all settings', function () {
    Setting::create([
        'group' => 'app',
        'key' => 'name',
        'value' => 'MyApp',
    ]);

    $this->artisan('settings:list')
        ->expectsTable(
            ['ID', 'Scope Type', 'Scope ID', 'Group', 'Key', 'Value'],
            [[
                'id' => 1,
                'scope_type' => null,
                'scope_id' => null,
                'group' => 'app',
                'key' => 'name',
                'value' => 'MyApp',
            ]]
        )
        ->assertExitCode(0);
});

it('clears all settings', function () {
    Setting::create([
        'group' => 'app',
        'key' => 'name',
        'value' => 'MyApp',
    ]);

    $this->artisan('settings:clear')
        ->expectsOutput('Deleted 1 setting(s).')
        ->assertExitCode(0);

    $this->assertDatabaseMissing('settings', [
        'group' => 'app',
        'key' => 'name',
    ]);
});

it('dumps settings as JSON', function () {
    Setting::create([
        'group' => 'app',
        'key' => 'name',
        'value' => 'MyApp',
    ]);

    $this->artisan('settings:dump --pretty')
        ->expectsOutput(json_encode([
            [
                'scope_type' => null,
                'scope_id' => null,
                'group' => 'app',
                'key' => 'name',
                'value' => 'MyApp',
            ]
        ], JSON_PRETTY_PRINT))
        ->assertExitCode(0);
});
