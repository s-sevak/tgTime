<?php

namespace App\Factory;

use App\EnvLoader\EnvLoader;
use App\Database\Database;

class DatabaseFactory
{
    public static function create(): Database
    {
        $envLoader = new EnvLoader();
        $envLoader->loadEnv();
        $dbSet = EnvLoader::getDbConnectData();

        return new Database(
            $dbSet['DB_HOST'],
            $dbSet['DB_NAME'],
            $dbSet['DB_USERNAME'],
            $dbSet['DB_PASSWORD']
        );
    }
}
