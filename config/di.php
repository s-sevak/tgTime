<?php

use App\Bootstrap\App;
use App\Database\Database;
use App\TelegramBot\TelegramBot;
use App\UserRepository\UserRepository;
use DI\ContainerBuilder;
use App\EnvLoader\EnvLoader;
use Psr\Container\ContainerInterface;

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

    Database::class => function () {
        return new Database(
            $_ENV['DB_HOST'],
            $_ENV['DB_NAME'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
    },

    TelegramBot::class => function () {
        return new TelegramBot(
            $_ENV['TELEGRAM_BOT_TOKEN'],
            $_ENV['TELEGRAM_BOT_BASE_URL']
        );
    },

    UserRepository::class => function (ContainerInterface $c) {
        return new UserRepository($c->get(PDO::class));
    },

    App::class => function (ContainerInterface $c) {
        return new App(
            $c->get(Database::class),
            $c->get(UserRepository::class),
            $c->get(TelegramBot::class)
        );
    }
]);

return $builder->build();
