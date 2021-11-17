<?php

namespace App\Migrations;

use App\Config\Dbh;
use App\Config\Scripts;

class m0002_update_user_table extends Dbh
{

    public function migrate()
    {
        Scripts::log('Updating user table. Adding fullname column...');
        $db_conn = $this->connect();
        $stmt = $db_conn->prepare("ALTER TABLE user ADD COLUMN IF NOT EXISTS fullname VARCHAR(32)");
        $stmt->execute();
    }

    public function drop()
    {
        Scripts::log('Dropping column: fullname from user table...');
        $db_conn = $this->connect();
        $stmt = $db_conn->prepare("ALTER TABLE user DROP COLUMN IF EXISTS fullname");
        $stmt->execute();
    }
}
