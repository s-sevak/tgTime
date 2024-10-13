<?php

interface TelegramBotInterface
{
    public function query($method, $params = []);

    public function getUpdates();

    public function sendMessage(string $chatId, string $text);
}
