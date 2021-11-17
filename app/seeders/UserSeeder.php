<?php

namespace App\Seeders;

use App\Config\Middleware;
use App\Core\Seeders;

class UserSeeder
{

    public static function seedUser()
    {

        // Data that will be seeded to the table, If you add any column into your table be sure to add its key and value or else it wil be seeded as 'NULL'
        Seeders::create('user',
        [
            'user_id'   => Middleware::generateId('admin'),
            'email'     => 'admin@email.com',
            'password'  => Middleware::hash('admin'),
            'fullname'  => '',
        ]);

        Seeders::create('user',
        [
            'user_id'   => Middleware::generateId('user'),
            'email'     => 'user@email.com',
            'password'  => Middleware::hash('user'),
            'fullname'  => '',
        ]);
    }
}
