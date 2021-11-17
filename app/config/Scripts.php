<?php 

namespace App\Config;

// To get informations on various scripts in terminal and their commands
class Scripts {

    // composer serve
    public static function serve() {
        // composer serve:local
        self::echo("serve:local => to run a local server on your machine.");
        // composer serve:remote
        self::echo("serve:remote => to run a server on your local network (the ip for access is your local ip address).");
    }
    // composer:migrate
    public static function migrate() {
        // composer migrate:apply
        self::echo("migrate:apply => to apply current migrations setup in app/migrations");
        // composer migrate:apply:seed
        self::echo("migrate:apply:seed => to apply current migrations setup in app/migrations and seed all data to the database");
        // composer migrate:apply:seed:user
        self::echo("migrate:apply:seed:user => to apply current migrations setup in app/migrations and seed user data to the database");
        // composer migrate:drop
        self::echo("migrate:drop => to drop all current tables created through migrations (*note: this action does not delete your migration files.).");
    }
    // composer seed
    public static function seed() {
        // composer seed:all
        self::echo("seed:all => to seed all data specified in seeders to the database");
        // composer seed:user
        self::echo("seed:user => to seed predefined data into the user table");
    }
    
    // composer make
    public static function make() {
        // composer make:migration
        self::echo("make:migration => to create a migration (create or update table)");
        // composer make:seeder
        self::echo("make:seeder => to create a seeder, to insert predefined data to a table");
        // composer make:controller
        self::echo("make:controller => to create a controller and model for a table");
    }

    // composer errors
    public static function errors() {
        // composer errors:on
        self::echo("errors:on => enable errors when working with the API");
        // composer errors:off
        self::echo("errors:off => disable errors when working with the API");
    }
    // composer responses
    public static function responses() {
        // composer responses:on
        self::echo("responses:on => enable responses when working with the API");
        // composer responses:off
        self::echo("responses:off => disable responses when working with the API");
    }
    
    // Message with date and time
    public static function log($message)
    {
        echo PHP_EOL . '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
    // Simple message
    public static function echo($message)
    {
        echo PHP_EOL . $message . PHP_EOL . PHP_EOL;
    }

}