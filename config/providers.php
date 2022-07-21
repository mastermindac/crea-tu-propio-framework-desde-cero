<?php

return [
    /**
     * Service providers that will run before booting application
     */
    'boot' => [
        /**
         * Lune framework service providers
         */
        Lune\Providers\ServerServiceProvider::class,
        Lune\Providers\DatabaseDriverServiceProvider::class,
        Lune\Providers\SessionStorageServiceProvider::class,
        Lune\Providers\ViewServiceProvider::class,
        Lune\Providers\AuthenticatorServiceProvider::class,
        Lune\Providers\HasherServiceProvider::class,
        Lune\Providers\FileStorageDriverServiceProvider::class,

        /**
         * Package service providers
         */
    ],

    /**
     * Service providers that will run after booting application
     */
    'runtime' => [
        App\Providers\RuleServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\AppServiceProvider::class
    ],

    'cli' => [
        Lune\Providers\DatabaseDriverServiceProvider::class,
    ]
];
