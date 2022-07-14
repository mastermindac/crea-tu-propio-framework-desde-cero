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

class MockModelFillable extends MockModel {
    protected ?string $table = "mock_models";
    protected array $fillable = ["test", "name"];
}

class ModelTest extends TestCase {
    use RefreshDatabase;

    protected ?DatabaseDriver $driver = null;

    private function createTestTable($name, $columns, $withTimestamps = true) {
        $sql = "CREATE TABLE $name (id INT AUTO_INCREMENT PRIMARY KEY, "
            . implode(", ", array_map(fn ($c) => "$c VARCHAR(256)", $columns));

        if ($withTimestamps) {
            $sql .= ", created_at DATETIME, updated_at DATETIME NULL";
        }

        $sql .= ")";

        $this->driver->statement($sql);
    }

    public function test_save_basic_model_with_attributes() {
        $this->createTestTable("mock_models", ["test", "name"]);
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

    /**
     * @depends test_save_basic_model_with_attributes
     */
    public function test_find_model() {
        $this->createTestTable("mock_models", ["test", "name"]);

        $expected = [
            [
                "id" => 1,
                "test" => "Test",
                "name" => "Name",
                "created_at" => date("Y-m-d H:m:s"),
                "updated_at" => null,
            ],
            [
                "id" => 2,
                "test" => "Foo",
                "name" => "Bar",
                "created_at" => date("Y-m-d H:m:s"),
                "updated_at" => null,
            ],
        ];

        foreach ($expected as $columns) {
            $model = new MockModel();
            $model->test = $columns["test"];
            $model->name = $columns["name"];
            $model->save();
        }

        foreach ($expected as $columns) {
            $model = new MockModel();
            foreach ($columns as $column => $value) {
                $model->{$column} = $value;
            }
            $this->assertEquals($model, MockModel::find($columns["id"]));
        }

        $this->assertNull(MockModel::find(5));
    }

    /**
     * @depends test_save_basic_model_with_attributes
     */
    public function test_create_model_with_no_fillable_attributes_throws_error() {
        $this->expectException(\Error::class);
        MockModel::create(["test" => "test"]);
    }

    /**
     * @depends test_create_model_with_no_fillable_attributes_throws_error
     */
    public function test_create_model() {
        $this->createTestTable("mock_models", ["test", "name"]);

        $model = MockModelFillable::create(["test" => "Test", "name" => "Name"]);
        $this->assertEquals(1, count($this->driver->statement("SELECT * FROM mock_models")));
        $this->assertEquals("Name", $model->name);
        $this->assertEquals("Test", $model->test);
    }


    /**
     * @depends test_create_model
     */
    public function test_all() {
        $this->createTestTable("mock_models", ["test", "name"]);

        MockModelFillable::create(["test" => "Test", "name" => "Name"]);
        MockModelFillable::create(["test" => "Test", "name" => "Name"]);
        MockModelFillable::create(["test" => "Test", "name" => "Name"]);

        $models = MockModelFillable::all();

        $this->assertEquals(3, count($models));

        foreach ($models as $model) {
            $this->assertEquals("Test", $model->test);
            $this->assertEquals("Name", $model->name);
        }
    }

    /**
     * @depends test_create_model
     */
    public function test_where_and_first_where() {
        $this->createTestTable("mock_models", ["test", "name"]);

        MockModelFillable::create(["test" => "First", "name" => "Name"]);
        MockModelFillable::create(["test" => "Where", "name" => "Foo"]);
        MockModelFillable::create(["test" => "Where", "name" => "Foo"]);

        $where = MockModelFillable::where("test", "Where");
        $this->assertEquals(2, count($where));
        $this->assertEquals("Where", $where[0]->test);
        $this->assertEquals("Where", $where[1]->test);

        $firstWhere = MockModelFillable::firstWhere('test', 'First');

        $this->assertEquals("First", $firstWhere->test);
    }

    /**
     * @depends test_create_model
     * @depends test_find_model
     */
    public function test_update() {
        $this->createTestTable("mock_models", ["test", "name"]);

        MockModelFillable::create(["test" => "test", "name" => "name"]);

        // The create method doesn't return the ID of the model.
        // Check https://www.php.net/manual/es/pdo.lastinsertid.php to implement that feature.
        $model = MockModelFillable::find(1);

        $model->test = "UPDATED test";
        $model->name = "UPDATED name";
        $model->update();

        $rows = $this->driver->statement("SELECT test, name FROM mock_models");
        $this->assertEquals(1, count($rows));
        $this->assertEquals(["test" => "UPDATED test", "name" => "UPDATED name"], $rows[0]);
    }

    /**
     * @depends test_create_model
     * @depends test_find_model
     */
    public function test_delete() {
        $this->createTestTable("mock_models", ["test", "name"]);

        MockModelFillable::create(["test" => "test", "name" => "name"]);

        // The create method doesn't return the ID of the model.
        // Check https://www.php.net/manual/es/pdo.lastinsertid.php to implement that feature.
        $model = MockModelFillable::find(1);

        $model->delete();

        $rows = $this->driver->statement("SELECT test, name FROM mock_models");
        $this->assertEquals(0, count($rows));
    }
}
