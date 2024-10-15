<?php

namespace App\Bootstrap;

use App\Database\Database;
use App\TelegramBot\TelegramBot;
use App\UserManager\UserManager;
use App\Handlers\TelegramHandler;

class App implements AppInterface
{
    private Database $db;
    private UserManager $userManager;
    private TelegramBot $telegramBot;
    private TelegramHandler $telegramHandler;

    public function __construct()
    {
        $this->db = new Database();
        $this->userManager = new UserManager($this->db->getConnection());
        $this->telegramBot = new TelegramBot();
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
