<?php

use Lune\Database\DB;
use Lune\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        DB::statement('CREATE TABLE test (id INT AUTO_INCREMENT PRIMARY KEY, test VARCHAR(256))');
    }

    public function down() {
        DB::statement('DROP TABLE test');
    }
};
