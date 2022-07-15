<?php

return [
    'boot' => [
        Lune\Providers\ServerServiceProvider::class,
        Lune\Providers\DatabaseDriverServiceProvider::class,
        Lune\Providers\SessionStorageServiceProvider::class,
        Lune\Providers\ViewServiceProvider::class,
    ],

    'runtime' => [

    ]
];
