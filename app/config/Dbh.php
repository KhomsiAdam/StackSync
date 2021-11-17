<?php

namespace App\Config;

use PDO;
use Exception;

// Database Handler
class Dbh
{
    public $conn = null;

    public function connect()
    {
        if (is_null($this->conn)) {
            try {
                $this->conn = new PDO($_ENV['DSN'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            } catch (Exception $e) {
                echo 'Database Connection Error: ' . $e->getMessage();
            }
        } else {
            return $this->conn;
        }
    }

}
