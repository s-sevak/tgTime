<?php

namespace App\Bootstrap;

use App\Database\Database;
use App\Factory\DatabaseFactory;
use App\Factory\TelegramBotFactory;
use App\TelegramBot\TelegramBot;
use App\UserRepository\UserRepository;
use App\Handlers\TelegramHandler;
use Exception;

class App implements AppInterface
{
    private Database $db;
    private UserRepository $userManager;
    private TelegramBot $telegramBot;
    private TelegramHandler $telegramHandler;

    public function __construct()
    {
        $this->db = DatabaseFactory::create();
        $this->userManager = new UserRepository($this->db->getConnection());
        $this->telegramBot = TelegramBotFactory::create();
        $this->telegramHandler = new TelegramHandler($this->userManager, $this->telegramBot);
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
