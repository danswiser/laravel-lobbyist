<?php

/*
|--------------------------------------------------------------------------
| Legislation Drivers
|--------------------------------------------------------------------------
| Here you may configure the settings for each driver.
*/

return [
    'drivers' => [
        /*
        |--------------------------------------------------------------------------
        | Default Legislation Driver
        |--------------------------------------------------------------------------
        | Here you may specify which of the drivers below you wish to use as your
        | default driver for all legislation operations.
        */
        'default' => env('LOBBYIST_DEFAULT_DRIVER', 'legiscan'),

        /*
        |--------------------------------------------------------------------------
        | Backup LegiScan Driver
        |--------------------------------------------------------------------------
        | LegiScan is a comprehensive legislative data platform that provides access
        | to legislative information from all 50 states and the federal government.
        | This driver integrates with the LegiScan API to fetch legislative data.
        */
        'legiscan' => [

            /*
            |--------------------------------------------------------------------------
            | API Endpoint Configuration
            |--------------------------------------------------------------------------
            |
            | Here you may configure your settings for connecting to the LegiScan API.
            | You will need to provide your API key, which you can obtain by
            | registering for an account on the LegiScan website.
            | https://legiscan.com/user/register
            |
            */
            'endpoint' => [
                'api_key' => env('LEGISCAN_API_KEY'),
                'base_uri' => env('LEGISCAN_BASE_URI', 'https://api.legiscan.com/'),
            ],

            /*
            |--------------------------------------------------------------------------
            | Request Configuration
            |--------------------------------------------------------------------------
            | Here you may configure the settings for HTTP requests made to the
            | LegiScan API.
            | You can adjust the timeout, number of retries, and retry sleep options.
            |
            */
            'request' => [
                'timeout' => 30,
                'retry_times' => 2,
                'retry_sleep_ms' => 200,
            ],
            
            /*
            |--------------------------------------------------------------------------
            | Caching Configuration
            |--------------------------------------------------------------------------
            | Here you may configure the settings for caching API responses.
            | You can enable or disable caching, choose the cache store,
            | and set the time-to-live (TTL) for cached items.
            |
            */
            'cache' => [
                'enabled' => env('LEGISCAN_CACHE_ENABLED', true),
                'store' => env('LEGISCAN_CACHE_STORE', env('CACHE_STORE')),
                'ttl' => env('LEGISCAN_CACHE_TTL', 3600),
            ],
        ],
    ],
];