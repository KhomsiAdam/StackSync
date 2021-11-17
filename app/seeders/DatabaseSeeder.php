<?php

namespace App\Seeders;

use App\Seeders\UserSeeder;

class DatabaseSeeder
{
    // To seed all to the database, add any new created seeder below
    public static function seedDatabase()
    {
        UserSeeder::seedUser();
    }
}