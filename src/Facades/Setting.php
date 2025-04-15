<?php

namespace DanieleMontecchi\LaravelScopedSettings\Facades;

use Illuminate\Support\Facades\Facade;

class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'scoped-settings';
    }
}
