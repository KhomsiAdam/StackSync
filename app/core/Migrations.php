<?php

namespace App\Core;

use PDO;
use Dotenv\Dotenv;
use App\Config\Dbh;
use App\Config\Scripts;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable('./');
$dotenv->load();

class Migrations
{

    // Apply Migrations method
    public static function applyMigrations()
    {
        // Connect to Database
        $db = new Dbh();
        $db->connect();
        // Create Migrations Table if it doesn't exist already
        $stmt = $db->conn->prepare("CREATE TABLE IF NOT EXISTS migrations (
            m_id VARCHAR(5) PRIMARY KEY NOT NULL,
            migration VARCHAR(32) NOT NULL,
            created_at TIMESTAMP default CURRENT_TIMESTAMP)");
        $stmt->execute();

        // Get all current applied migrations in the database
        $stmt = $db->conn->prepare("SELECT migration FROM migrations");
        $stmt->execute();

        $applied_migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // New migrations
        $new_migrations = [];
        // Get the local migrations files
        $migrations = scandir(dirname(dirname(__DIR__)) . '/app/migrations');
        // Compare with the current applied migrations
        $updated_migrations = array_diff($migrations, $applied_migrations);

        // Apply each migration
        foreach ($updated_migrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            require_once dirname(__DIR__) . '/migrations/' . $migration;
            $migration_class = '\\App\Migrations\\' . pathinfo($migration, PATHINFO_FILENAME);
            $class_instance = new $migration_class;
            Scripts::log("Applying migration : $migration" . '...');
            $class_instance->migrate();
            Scripts::log("Migration : $migration" . ', was applied successfully.');
            $new_migrations[] = $migration;
        }

        // Record the migrations in the migrations table
        if (!empty($new_migrations)) {
            $migrations_ids = $new_migrations;
            for ($i = 0; $i < count($new_migrations); $i++) {
                $migrations_ids[$i] = substr($migrations_ids[$i], 0, 5);
                $stmt = $db->conn->prepare("INSERT INTO migrations (m_id, migration) VALUES ('$migrations_ids[$i]', '$new_migrations[$i]')");
                $stmt->execute();
            }
        } else {
            Scripts::log('All migrations are already applied.');
        }
    }

    // Drop Migrations method
    public static function dropMigrations()
    {   
        // The migrations table and all tables generated from migrations will be dropped
        Scripts::echo("Are you sure you want to drop your migrations tables and tables generated from it? [ yes, no ]");
        $input = fgets(STDIN);
        if (trim($input) == "yes") {
            // Connect to Database
            $db = new Dbh();
            $db->connect();
            // Check if migrations table exists
            $stmt_exists = $db->conn->prepare("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$_ENV[DB_NAME]' AND TABLE_NAME = 'migrations'");
            $stmt_exists->execute();
            $migration_exists = $stmt_exists->fetch();
            if (!empty($migration_exists)) {
                // Get all migrations
                $stmt1 = $db->conn->prepare("SELECT migration FROM migrations");
                $stmt1->execute();
                $current_migrations = $stmt1->fetchAll(PDO::FETCH_COLUMN);
                // Drop the migrations table
                $stmt2 = $db->conn->prepare("DROP TABLE IF EXISTS migrations;");
                $stmt2->execute();
                // Drop each table generated by migration by reverse (from last to first)
                foreach (array_reverse($current_migrations) as $migration) {
                    require_once dirname(__DIR__) . '/migrations/' . $migration;
                    $migration_class = '\\App\Migrations\\' . pathinfo($migration, PATHINFO_FILENAME);
                    $class_instance = new $migration_class;
                    Scripts::log("Dropping migration : $migration" . '...');
                    $class_instance->drop();
                    Scripts::log("Migration : $migration" . ', was dropped successfully.');
                }
                Scripts::log("All migrations and tables generated from it have been dropped.");
            } else {
                Scripts::log("There is no migrations currently applied. Create migrations files and/or run the composer migrations:apply command.");
            }
        } else {
            exit;
        }
    }
}
