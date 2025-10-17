<?php declare(strict_types=1);

$container = require(__DIR__ . '/../bootstrap.php');

$application = $container->get(Symfony\Component\Console\Application::class);

$application->run();
