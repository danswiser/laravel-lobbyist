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
            'endpoint' => [
                'api_key' => env('LEGISCAN_API_KEY'),
                'base_uri' => env('LEGISCAN_BASE_URI', 'https://api.legiscan.com/'),
            ],
            'request' => [
                'timeout' => 30,
                'retry_times' => 2,
                'retry_sleep_ms' => 200,
            ],
        ],
    ],
];