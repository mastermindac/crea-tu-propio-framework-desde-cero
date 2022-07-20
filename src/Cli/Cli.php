<?php

namespace Lune\Cli;

use Dotenv\Dotenv;
use Lune\App;
use Lune\Cli\Commands\MakeMigration;
use Lune\Cli\Commands\Migrate;
use Lune\Cli\Commands\MigrateRollback;
use Lune\Config\Config;
use Lune\Database\Drivers\DatabaseDriver;
use Lune\Database\Migrations\Migrator;
use Symfony\Component\Console\Application;

class Cli {
    public static function bootstrap(string $root): self {
        App::$root = $root;
        Dotenv::createImmutable($root)->load();
        Config::load($root . "/config");

        foreach (config("providers.cli") as $provider) {
            (new $provider())->registerServices();
        }

        app(DatabaseDriver::class)->connect(
            config("database.connection"),
            config("database.host"),
            config("database.port"),
            config("database.database"),
            config("database.username"),
            config("database.password"),
        );

        singleton(
            Migrator::class,
            fn () => new Migrator(
                "$root/database/migrations",
                resourcesDirectory() . "/templates",
                app(DatabaseDriver::class)
            )
        );

        return new self();
    }

    public function run() {
        $cli = new Application("Lune");

        $cli->addCommands([
            new MakeMigration(),
            new Migrate(),
            new MigrateRollback()
        ]);

        $cli->run();
    }
}
