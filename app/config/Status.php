<?php

namespace App\Config;

use App\Config\Scripts;

// Errors and Responses status
class Status
{
    public static function status($current, $replacement)
    {
        // Get the .env file
        $env = file_get_contents(dirname(dirname(__DIR__)) . '/.env');

        // Find and replace status
        $env = str_replace($current, $replacement, $env);

        // Write into the file
        file_put_contents('.env', $env);
    }
    // Errors
    public static function errorsOn()
    {
        self::status('ERRORS=OFF', 'ERRORS=ON');
        Scripts::echo("Errors have been enabled.");
    }
    public static function errorsOff()
    {
        self::status('ERRORS=ON', 'ERRORS=OFF');
        Scripts::echo("Errors have been disabled.");
    }
    // Responses
    public static function responsesOn()
    {
        self::status('RESPONSES=OFF', 'RESPONSES=ON');
        Scripts::echo("Responses have been enabled.");
    }
    public static function responsesOff()
    {
        self::status('RESPONSES=ON', 'RESPONSES=OFF');
        Scripts::echo("Responses have been disabled.");
    }
}
