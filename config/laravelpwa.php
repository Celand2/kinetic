<?php

return [
    'name' => 'KTS',
    'manifest' => [
        'name' => env('APP_NAME', 'Kinetic Trading System'),
        'short_name' => 'KTS',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation'=> 'portrait',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => '/images/icons/icons/icon-72x72.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/images/icons/icons/icon-96x96.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/images/icons/icons/icon-128x128.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/images/icons/icons/icon-144x144.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/images/icons/icons/icon-152x152.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/images/icons/icons/icon-192x192.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/images/icons/icons/icon-384x384.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/images/icons/icons/icon-512x512.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/images/icons/splash/splash-640x1136.png',
            '750x1334' => '/images/icons/splash/splash-750x1334.png',
            '828x1792' => '/images/icons/splash/splash-828x1792.png',
            '1125x2436' => '/images/icons/splash/splash-1125x2436.png',
            '1242x2208' => '/images/icons/splash/splash-1242x2208.png',
            '1242x2688' => '/images/icons/splash/splash-1242x2688.png',
            '1536x2048' => '/images/icons/splash/splash-1536x2048.png',
            '1668x2224' => '/images/icons/splash/splash-1668x2224.png',
            '1668x2388' => '/images/icons/splash/splash-1668x2388.png',
            '2048x2732' => '/images/icons/splash/splash-2048x2732.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Dashboard',
                'description' => 'Accéder au tableau de bord',
                'url' => '/client/dashboard',
                'icons' => [
                    "src" => "/images/icons/icons/icon-72x72.png",
                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Admin',
                'description' => 'Panneau administrateur',
                'url' => '/admin/dashboard'
            ]
        ],
        'custom' => []
    ]
];

?>
