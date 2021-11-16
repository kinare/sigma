<?php

return [
    /*
     * This is the base configuration for the default Sigma sync manager
     * Use this to set which implementation is in use e.g. Sigma Transport for Nav
     */

    /* Sigma Connection */
    'connection' => [
        'base_url' => env('SIGMA_URL', 'http://sigmaendpoint/example.com'),
        'username' => env('SIGMA_USER', 'user'),
        'password' => env('SIGMA_PASSWORD', 'user'),
    ],

    'services' => env('SIGMA_SERVICE', []),

    'api' => [
        'provider' => env('SIGMA_PROVIDERS', 'providers'),
        'wrappers' => env('SIGMA_WRAPPERS', 'wrappers'),
        'fields' => env('SIGMA_FIELDS', 'fields'),
        'childWrappers' => env('SIGMA_CHILD_WRAPPERS', 'childWrappers'),
    ]
];
