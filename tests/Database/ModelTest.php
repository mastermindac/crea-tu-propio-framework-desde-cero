<?php

namespace Lune\Tests\Database;

use Lune\Database\Drivers\DatabaseDriver;
use Lune\Database\Drivers\PdoDriver;
use Lune\Database\Model;
use PDOException;
use PHPUnit\Framework\TestCase;

class MockModel extends Model {
    //
}

class ModelTest extends TestCase {
    protected ?DatabaseDriver $driver = null;

    protected function setUp(): void {
        if (is_null($this->driver)) {
            $this->driver = new PdoDriver();
            Model::setDatabaseDriver($this->driver);
            try {
                $this->driver->connect('mysql', 'localhost', 3306, 'curso_framework_tests', 'root', '');
            } catch (PDOException $e) {
                $this->markTestSkipped("Can't connect to test database: {$e->getMessage()}");
            }
        }
    }

    protected function tearDown(): void {
        $this->driver->statement("DROP DATABASE IF EXISTS curso_framework_tests");
        $this->driver->statement("CREATE DATABASE curso_framework_tests");
    }

    private function createTestTable($name, $columns, $withTimestamps = false) {
        $sql = "CREATE TABLE $name (id INT AUTO_INCREMENT PRIMARY KEY, "
            . implode(", ", array_map(fn ($c) => "$c VARCHAR(256)", $columns));

        if ($withTimestamps) {
            $sql .= ", created_at DATETIME, updated_at DATETIME NULL";
        }

        $sql .= ")";

        $this->driver->statement($sql);
    }

    public function test_save_basic_model_with_attributes() {
        $this->createTestTable("mock_models", ["test", "name"], true);
        $model = new MockModel();
        $model->test = "Test";
        $model->name = "Name";
        $model->save();

        $rows = $this->driver->statement("SELECT * FROM mock_models");

        $expected = [
            "id" => 1,
            "name" => "Name",
            "test" => "Test",
            "created_at" => date("Y-m-d H:m:s"),
            "updated_at" => null,
        ];

        $this->assertEquals($expected, $rows[0]);
        $this->assertEquals(1, count($rows));
    }
}
