<?php

use DanieleMontecchi\LaravelScopedSettings\Facades\Setting;

if (!function_exists('setting')) {
    function setting(): \DanieleMontecchi\LaravelScopedSettings\Managers\SettingsManager
    {
        return Setting::getFacadeRoot();
    }
}
