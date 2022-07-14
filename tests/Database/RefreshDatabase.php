<?php

namespace Lune\Tests\Database;

use Lune\Database\Drivers\PdoDriver;
use Lune\Database\Model;
use PDOException;

trait RefreshDatabase {
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
}
