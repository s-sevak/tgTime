<?php

require_once __DIR__ . '/TelegramBotInterface.php';
require_once __DIR__ . "/../EnvLoader/EnvLoader.php";

class TelegramBot implements TelegramBotInterface
{
    protected ?string $baseUrlWithToken;

    protected ?string $updateId = null;

    public function __construct()
    {
        $envLoader = (new EnvLoader())->loadEnv();
        $telegramBotData = EnvLoader::getTelegramBotData();

        $this->baseUrlWithToken =
            $telegramBotData['TELEGRAM_BOT_BASE_URL'] .
            $telegramBotData['TELEGRAM_BOT_TOKEN'] .
            '/';
    }

    public function query($method, $params = [])
    {
        $url = $this->baseUrlWithToken . $method;

        if (!empty($params)) {
            $url .= "?" . http_build_query($params);
        }

        return json_decode(file_get_contents($url));
    }

    public function getUpdates()
    {
        $response = $this->query('getUpdates', [
            'offset' => $this->updateId + 1
        ]);

        if (!empty($response->result)) {
            $this->updateId = $response->result[count($response->result) - 1]->update_id;
        }

        return $response->result;
    }

    public function sendMessage(string $chatId, string $text)
    {
        return $this->query('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
