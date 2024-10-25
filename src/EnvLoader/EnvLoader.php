<?php

namespace App\EnvLoader;

use Exception;

class EnvLoader implements EnvLoaderInterface
{
    private static ?array $dbConnectData;
    private static ?array $telegramBotData;
    private string $filePath;

    public function __construct(string $filePath = null)
    {
        $this->filePath = $filePath ?? dirname(__DIR__) . '/../.env';
    }

    public function loadEnv(): void
    {
        if (!file_exists($this->filePath)) {
            throw new Exception("не найден файл .env");
        }

        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $dbConnectData = [];
        $telegramBotData = [];

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2) + [null, null];
            $key = trim($key);
            $value = trim($value);

            if ($key === null || $value === null) {
                continue;
            }

            if (str_starts_with($key, 'DB_')) {
                $dbConnectData[$key] = $value;
            } elseif (str_starts_with($key, 'TELEGRAM_BOT_')) {
                $telegramBotData[$key] = $value;
            }
        }
        self::$dbConnectData = $dbConnectData;
        self::$telegramBotData = $telegramBotData;
    }

    public static function getDbConnectData(): ?array
    {
        return self::$dbConnectData;
    }

    public static function getTelegramBotData(): ?array
    {

        return self::$telegramBotData;
    }
}
