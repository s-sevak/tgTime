<?php

namespace App\Bootstrap;

use App\Database\Database;
use App\Factory\DatabaseFactory;
use App\Factory\TelegramBotFactory;
use App\TelegramBot\TelegramBot;
use App\UserRepository\UserRepository;
use App\Handlers\TelegramHandler;
use Exception;
use Psr\Container\ContainerInterface;

class App implements AppInterface
{
    private Database $db;
    private UserRepository $userManager;
    private TelegramBot $telegramBot;
    private TelegramHandler $telegramHandler;

    public function __construct(ContainerInterface $container)
    {
        $this->db = DatabaseFactory::create($container);
        $this->telegramBot = TelegramBotFactory::create($container);
        $this->userManager = new UserRepository($this->db->getConnection());
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
