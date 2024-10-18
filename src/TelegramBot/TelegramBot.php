<?php

namespace App\TelegramBot;

class TelegramBot implements TelegramBotInterface
{
    protected string $baseUrlWithToken;
    protected ?int $updateId = null;

    public function __construct(string $token)
    {
        $this->baseUrlWithToken = "https://api.telegram.org/bot{$token}/";
    }

    public function query(string $method, array $params = []): ?object
    {
        $url = $this->baseUrlWithToken . $method;

        if (!empty($params)) {
            $url .= "?" . http_build_query($params);
        }

        return json_decode(file_get_contents($url));
    }

    public function getUpdates(): ?array
    {
        $response = $this->query('getUpdates', [
            'offset' => $this->updateId + 1
        ]);

        if (!empty($response->result)) {
            $this->updateId = $response->result[count($response->result) - 1]->update_id;
        }

        return $response->result ?? null;
    }

    public function sendMessage(string $chatId, string $text): ?object
    {
        return $this->query('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
