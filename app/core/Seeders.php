<?php

namespace App\Core;

use PDO;
use Dotenv\Dotenv;
use App\Config\Dbh;
use App\Config\Scripts;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable('./');
$dotenv->load();


class Seeders
{

    // To create a record when seeding
    public static function create($table, $records = [])
    {
        // Connect to the database
        $db = new Dbh();
        $db_conn = $db->connect();

        // Get columns from table
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$_ENV[DB_NAME]' AND TABLE_NAME='$table'";

        $stmt = $db_conn->prepare($sql);
        $stmt->execute();
        $table_cols = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Remove date columns
        foreach ($table_cols as $key => $value) {
            if (strpos($value, '_updated_at') !== false || strpos($value, '_created_at') !== false) unset($table_cols[$key]);
        }

        // Implode table columns array with comma to get table columns mysql syntax for INSERT
        $columns = implode(', ', $table_cols);

        // Get the values only to insert
        $records_values = [];
        foreach ($records as $record) {
            array_push($records_values, $record);
        }

        // Check if tables columns count is higher than records values
        if (count($table_cols) > count($records_values)) {
            for ($i = 1; $i <= count($table_cols) - count($records_values); $i++) {
                // to insert NULL data into column with no value specified
                array_push($records_values, NULL);
            }
        }

        // Implode the records values array with comma and single quotes to get mysql syntax for VALUES
        $values = implode("', '", $records_values);

        // When seeding into user table, check if user already exists
        if ($table === 'user') {
            $sql = "SELECT email FROM $table";
            $stmt = $db_conn->prepare($sql);
            $stmt->execute();
            $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array($records['email'], $emails)) {
                $query = "INSERT INTO $table ($columns) VALUES ('$values')";

                $stmt = $db_conn->prepare($query);
                $stmt->execute();

                Scripts::log("$table seeded successfully.");
            } else {
                Scripts::log("this $table has already been seeded.");
            }
        } else {
            $query = "INSERT INTO $table ($columns) VALUES ('$values')";

            $stmt = $db_conn->prepare($query);
            $stmt->execute();

            Scripts::log("$table seeded successfully.");
        }
    }
}
