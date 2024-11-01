<?php

use App\Database\Database;
use App\TelegramBot\TelegramBot;
use DI\ContainerBuilder;
use App\EnvLoader\EnvLoader;

$builder = new ContainerBuilder();

$envLoader = new EnvLoader();
$envLoader->loadEnv();

$builder->addDefinitions([

    EnvLoader::class => $envLoader,

    PDO::class => function () {
        $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        return new PDO($dsn, $username, $password);
    },

    Database::class => DI\autowire(Database::class)
        ->constructor(
            DI\env('DB_HOST'),
            DI\env('DB_NAME'),
            DI\env('DB_USERNAME'),
            DI\env('DB_PASSWORD')
        ),

    TelegramBot::class => DI\autowire(TelegramBot::class)
        ->constructor(
            DI\env('TELEGRAM_BOT_TOKEN'),
            DI\env('TELEGRAM_BOT_BASE_URL')
        ),
]);

return $builder->build();
