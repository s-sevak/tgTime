<?php

namespace App\EnvLoader;

interface EnvLoaderInterface
{
    public function loadEnv(): void;

    public static function getDbConnectData(): ?array;

    public static function getTelegramBotData(): ?array;
}
