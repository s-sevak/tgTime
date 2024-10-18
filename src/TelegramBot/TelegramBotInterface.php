<?php

namespace App\TelegramBot;

interface TelegramBotInterface
{
    public function query(string $method, array $params = []): ?object;

    public function getUpdates(): ?array;

    public function sendMessage(string $chatId, string $text): ?object;
}
