<?php

use Lune\Database\DB;
use Lune\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        DB::statement('
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(256),
                email VARCHAR(256),
                password VARCHAR(256),
                created_at DATETIME,
                updated_at DATETIME NULL
            )
        ');
    }

    public function down() {
        DB::statement('DROP TABLE users');
    }
};
