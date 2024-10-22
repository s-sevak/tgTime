<?php

use App\Bootstrap\App;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new DI\Container();

$app = $container->get(App::class);
$app->run();
