<?php

require_once __DIR__ . '/../src/Database/Database.php';
require_once __DIR__ . '/../src/TelegramBot/TelegramBot.php';
require_once __DIR__ . '/../src/UserManager/UserManager.php';
require_once __DIR__ . '/../src/Handlers/TelegramHandler.php';
require_once __DIR__ . '/../src/Bootstrap/App.php';

$app = new App();
$app->run();
