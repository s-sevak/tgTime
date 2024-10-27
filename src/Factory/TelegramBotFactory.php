<?php
//
//namespace App\Factory;
//
//use App\EnvLoader\EnvLoaderInterface;
//use App\TelegramBot\TelegramBot;
//
//class TelegramBotFactory
//{
//    public static function create(EnvLoaderInterface $envLoader): TelegramBot
//    {
//        $envLoader->loadEnv();
//        $telegramBotData = $envLoader::getTelegramBotData();
//
//        return new TelegramBot($telegramBotData['TELEGRAM_BOT_TOKEN']);
//    }
//}
