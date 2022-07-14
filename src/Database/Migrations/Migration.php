<?php

namespace Lune\Database\Migrations;

interface Migration {
    public function up();
    public function down();
}
