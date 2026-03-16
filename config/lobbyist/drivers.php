<?php

/*
|--------------------------------------------------------------------------
| Legislation Drivers
|--------------------------------------------------------------------------
| Here you may configure the settings for each driver.
*/

return [
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
];