<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | These settings control the caching behavior for the settings.
    | You can define different TTL (time to live) values for global and scoped settings.
    | If set to `null`, the value will never be cached unless a TTL is explicitly
    | provided as the third argument to the `set()` method.
    |
    | You can also override these values using environment variables:
    | - SETTINGS_CACHE_GLOBAL_TTL
    | - SETTINGS_CACHE_SCOPED_TTL
    |
    */

    'cache' => [

        // TTL in seconds for globally scoped settings (null = no cache unless explicitly set)
        'global_ttl' => env('SETTINGS_CACHE_GLOBAL_TTL', null),

        // TTL in seconds for scoped settings (e.g. per user, per model). (null = no cache unless explicitly set)
        'scoped_ttl' => env('SETTINGS_CACHE_SCOPED_TTL', null),

    ],

];
