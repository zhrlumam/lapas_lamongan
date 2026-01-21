<?php

return [
    /*
    |--------------------------------------------------------------------------
    | iLovePDF API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk 2 akun iLovePDF API
    | Sistem akan otomatis switch ke akun kedua jika akun pertama limit
    |
    */

    'accounts' => [
        [
            'public_key' => env('ILOVEPDF_PUBLIC_KEY_1', ''),
            'secret_key' => env('ILOVEPDF_SECRET_KEY_1', ''),
            'name' => 'Account 1',
        ],
        [
            'public_key' => env('ILOVEPDF_PUBLIC_KEY_2', ''),
            'secret_key' => env('ILOVEPDF_SECRET_KEY_2', ''),
            'name' => 'Account 2',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */
    
    'timeout' => 120, // seconds
    'verify_ssl' => true,
];
