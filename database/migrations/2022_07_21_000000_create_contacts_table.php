<?php

use Lune\Database\DB;
use Lune\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        DB::statement('
            CREATE TABLE contacts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(256),
                phone_number VARCHAR(256),
                user_id INT NOT NULL,
                created_at DATETIME,
                updated_at DATETIME NULL,

                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ');
    }

    public function down() {
        DB::statement('DROP TABLE contacts');
    }
};
