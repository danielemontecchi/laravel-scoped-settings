<?php

namespace DanieleMontecchi\LaravelScopedSettings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \DanieleMontecchi\LaravelScopedSettings\Managers\SettingsManager for(object $model)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static void set(string $key, mixed $value)
 * @method static void forget(string $key)
 * @method static array all()
 * @method static array group(string $group)
 *
 * @see \DanieleMontecchi\LaravelScopedSettings\Managers\SettingsManager
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-scoped-settings';
    }
}
