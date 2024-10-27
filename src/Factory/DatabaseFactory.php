<?php
//
//namespace App\Factory;
//
//use App\Database\Database;
//use App\EnvLoader\EnvLoaderInterface;
//
//class DatabaseFactory
//{
//    public static function create(EnvLoaderInterface $envLoader): Database
//    {
//        $envLoader->loadEnv();
//        $dbSet = $envLoader::getDbConnectData();
//
//        return new Database(
//            $dbSet['DB_HOST'],
//            $dbSet['DB_NAME'],
//            $dbSet['DB_USERNAME'],
//            $dbSet['DB_PASSWORD']
//        );
//    }
//}
