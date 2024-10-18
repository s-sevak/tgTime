<?php

namespace App\Factory;

use App\EnvLoader\EnvLoader;
use App\TelegramBot\TelegramBot;

class TelegramBotFactory
{
    public static function create(): TelegramBot
    {
        $envLoader = new EnvLoader();
        $envLoader->loadEnv();
        $telegramBotData = EnvLoader::getTelegramBotData();

        return new TelegramBot($telegramBotData['TELEGRAM_BOT_TOKEN']);
    }
}
