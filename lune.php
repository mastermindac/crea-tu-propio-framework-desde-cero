<?php

require_once "./vendor/autoload.php";

use Lune\Database\Migrations\Migrator;

$migrator = new Migrator(
    __DIR__ . "/database/migrations",
    __DIR__ . "/templates",
);

if ($argv[1] == "make:migration") {
    $migrator->make($argv[2]);
}
