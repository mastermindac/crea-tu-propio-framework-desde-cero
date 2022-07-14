<?php

namespace Lune\Database\Migrations;

use Lune\Database\Drivers\DatabaseDriver;

class Migrator {
    public function __construct(
        private string $migrationsDirectory,
        private string $templatesDirectory,
        private DatabaseDriver $driver,
    ) {
        $this->migrationsDirectory = $migrationsDirectory;
        $this->templatesDirectory = $templatesDirectory;
        $this->driver = $driver;
    }

    private function log(string $message) {
        print($message . PHP_EOL);
    }

    private function createMigrationsTableIfNotExists() {
        $this->driver->statement("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(256))");
    }

    public function migrate() {
        $this->createMigrationsTableIfNotExists();
        $migrated = $this->driver->statement("SELECT * FROM migrations");
        $migrations = glob("$this->migrationsDirectory/*.php");

        if (count($migrated) >= count($migrations)) {
            $this->log("Nothing to migrate");
            return;
        }

        foreach (array_slice($migrations, count($migrated)) as $file) {
            $migration = require $file;
            $migration->up();
            $name = basename($file);
            $this->driver->statement("INSERT INTO migrations (name) VALUES (?)", [$name]);
            $this->log("Migrated => $name");
        }
    }

    public function make(string $migrationName) {
        $migrationName = snake_case($migrationName);

        $template = file_get_contents("$this->templatesDirectory/migration.php");

        if (preg_match("/create_.*_table/", $migrationName)) {
            $table = preg_replace_callback("/create_(.*)_table/", fn ($match) => $match[1], $migrationName);
            $template = str_replace('$UP', "CREATE TABLE $table (id INT AUTO_INCREMENT PRIMARY KEY)", $template);
            $template = str_replace('$DOWN', "DROP TABLE $table", $template);
        } elseif (preg_match("/.*(from|to)_(.*)_table/", $migrationName)) {
            $table = preg_replace_callback("/.*(from|to)_(.*)_table/", fn ($match) => $match[2], $migrationName);
            $template = preg_replace('/\$UP|\$DOWN/', "ALTER TABLE $table", $template);
        } else {
            $template = preg_replace_callback("/DB::statement.*/", fn ($match) => "// {$match[0]}", $template);
        }

        $date = date("Y_m_d");
        $id = 0;

        foreach (glob("$this->migrationsDirectory/*.php") as $file) {
            if (str_starts_with(basename($file), $date)) {
                $id++;
            }
        }

        $fileName = sprintf("%s_%06d_%s.php", $date, $id, $migrationName);

        file_put_contents("$this->migrationsDirectory/$fileName", $template);

        return $fileName;
    }
}
