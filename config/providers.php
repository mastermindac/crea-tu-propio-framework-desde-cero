<?php

return [
    'boot' => [
        Lune\Providers\ServerServiceProvider::class,
        Lune\Providers\DatabaseDriverServiceProvider::class,
        Lune\Providers\SessionStorageServiceProvider::class,
        Lune\Providers\ViewServiceProvider::class,
        Lune\Providers\AuthenticatorServiceProvider::class,
        Lune\Providers\HasherServiceProvider::class,
        Lune\Providers\FileStorageDriverServiceProvider::class,
    ],

    'runtime' => [
        App\Providers\RuleServiceProvider::class,
        App\Providers\RouteServiceProvider::class
    ],

    'cli' => [
        Lune\Providers\DatabaseDriverServiceProvider::class,
    ]
];
