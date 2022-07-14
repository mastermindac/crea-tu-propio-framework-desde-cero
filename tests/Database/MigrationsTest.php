<?php

namespace Lune\Tests\Database;

use Lune\Database\Drivers\DatabaseDriver;
use Lune\Database\Drivers\PdoDriver;
use Lune\Database\Migrations\Migrator;
use PHPUnit\Framework\TestCase;

class MigrationsTest extends TestCase {
    use RefreshDatabase {
        setUp as protected dbSetUp;
        tearDown as protected dbTearDown;
    }

    protected ?DatabaseDriver $driver = null;
    protected $templatesDirectory = __DIR__ . "/templates";
    protected $migrationsDirectory = __DIR__ . "/migrations";
    protected $expectedMigrations = __DIR__ . "/expected";
    protected Migrator $migrator;

    protected function setUp(): void {
        if (!file_exists($this->migrationsDirectory)) {
            mkdir($this->migrationsDirectory);
        }

        $this->dbSetUp();

        $this->migrator = new Migrator(
            $this->migrationsDirectory,
            $this->templatesDirectory,
            $this->driver
        );
    }

    protected function tearDown(): void {
        shell_exec("rm -r $this->migrationsDirectory");
        $this->dbTearDown();
    }

    public function migrationNames() {
        return [
            [
                "create_products_table",
                "$this->expectedMigrations/create_products_table.php",
            ],
            [
                "add_category_to_products_table",
                "$this->expectedMigrations/add_category_to_products_table.php",
            ],
            [
                "remove_name_from_products_table",
                "$this->expectedMigrations/remove_name_from_products_table.php",
            ],
        ];
    }

    /**
     * @dataProvider migrationNames
     */
    public function test_creates_migration_files($name, $expectedMigrationFile) {
        $expectedName = sprintf("%s_%06d_%s.php", date('Y_m_d'), 0, $name);
        $this->migrator->make($name);

        $file = "$this->migrationsDirectory/$expectedName";

        $this->assertFileExists($file);
        $this->assertFileEquals($expectedMigrationFile, $file);
    }
}
