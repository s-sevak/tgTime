<?php

use App\Bootstrap\App;
use App\Database\Database;
use App\TelegramBot\TelegramBot;
use App\UserRepository\UserRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use App\Factory\DatabaseFactory;
use App\Factory\TelegramBotFactory;
use App\EnvLoader\EnvLoader;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    'EnvLoader' => DI\create(EnvLoader::class),

    'Database' => DI\factory([DatabaseFactory::class, 'create'])
        ->parameter('envLoader', DI\get('EnvLoader')),

    'TelegramBot' => DI\factory([TelegramBotFactory::class, 'create'])
        ->parameter('envLoader', DI\get('EnvLoader')),

    'UserRepository' => function (ContainerInterface $c) {
        return new UserRepository($c->get('Database')->getConnection());
    },

    'App' => function (ContainerInterface $c) {
        return new App(
            $c->get('Database'),
            $c->get('UserRepository'),
            $c->get('TelegramBot')
        );
    },
]);

return $builder->build();
