<?php

namespace App\Factory;

use App\EnvLoader\EnvLoader;
use App\TelegramBot\TelegramBot;
use Psr\Container\ContainerInterface;

class TelegramBotFactory
{
    public static function create(ContainerInterface $container): TelegramBot
    {
        $envLoader = $container->get(EnvLoader::class);
        $envLoader->loadEnv();
        $telegramBotData = EnvLoader::getTelegramBotData();

        return new TelegramBot($telegramBotData['TELEGRAM_BOT_TOKEN']);
    }
}
