<?php

declare(strict_types=1);

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;

require dirname(__DIR__).'/vendor/autoload.php';

$environment = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? $environment !== 'prod');

$application = new Application(new Kernel($environment, $debug));
$application->run();
