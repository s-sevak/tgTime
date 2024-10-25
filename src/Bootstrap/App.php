<?php

namespace App\Bootstrap;

use App\Database\Database;
use App\TelegramBot\TelegramBot;
use App\UserRepository\UserRepository;
use App\Handlers\TelegramHandler;
use Exception;

class App implements AppInterface
{
    private Database $db;
    private UserRepository $userRepository;
    private TelegramBot $telegramBot;
    private TelegramHandler $telegramHandler;

    public function __construct(Database $db, UserRepository $userRepository, TelegramBot $telegramBot)
    {
        $this->db = $db;
        $this->userRepository = $userRepository;
        $this->telegramBot = $telegramBot;
        $this->telegramHandler = new TelegramHandler($this->userRepository, $this->telegramBot);
    }

    public function run(): void
    {
        try {
            sleep(2);
            $this->telegramHandler->processUpdates();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
