<?php

use App\Bootstrap\App;
use App\Database\Database;
use App\TelegramBot\TelegramBot;
use App\UserRepository\UserRepository;
use DI\ContainerBuilder;
//use App\Factory\DatabaseFactory;
//use App\Factory\TelegramBotFactory;
use App\EnvLoader\EnvLoader;

$builder = new ContainerBuilder();

$envLoader = new EnvLoader();
$envLoader->loadEnv();
$dbConfig = EnvLoader::getDbConnectData();
$telegramConfig = EnvLoader::getTelegramBotData();

$builder->addDefinitions([
//    EnvLoader::class => DI\create(EnvLoader::class),
    EnvLoader::class => $envLoader,

    Database::class => DI\autowire(Database::class)
        ->constructor(
            $dbConfig['DB_HOST'],
            $dbConfig['DB_NAME'],
            $dbConfig['DB_USERNAME'],
            $dbConfig['DB_PASSWORD']
        ),

    TelegramBot::class => DI\autowire(TelegramBot::class)
        ->constructor($telegramConfig['TELEGRAM_BOT_TOKEN']),

    UserRepository::class => DI\autowire(UserRepository::class),

    App::class => DI\autowire(App::class),
]);

return $builder->build();
