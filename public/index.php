<?php

use App\Bootstrap\App;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/di.php';

$container = require __DIR__ . '/../config/di.php';

$app = $container->get(App::class);
$app->run();
