<?php

namespace App\Migrations;

use App\Config\Dbh;
use App\Config\Scripts;

class m0001_create_user_table extends Dbh
{

    public function migrate()
    {
        Scripts::log("Creating user table...");
        $db_conn = $this->connect();
        $stmt = $db_conn->prepare("CREATE TABLE IF NOT EXISTS user (
                user_id VARCHAR(16) PRIMARY KEY NOT NULL,
                email VARCHAR(32) NOT NULL UNIQUE,
                password VARCHAR(128) NOT NULL,
                user_created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                user_updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)");
        $stmt->execute();
    }
    
    public function drop()
    {
        Scripts::log("Dropping user table...");
        $db_conn = $this->connect();
        $stmt = $db_conn->prepare("DROP TABLE IF EXISTS user");
        $stmt->execute();
    }
}
