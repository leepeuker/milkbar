<?php declare(strict_types=1);

$container = require(__DIR__ . '/../bootstrap.php');

$application = $container->get(Symfony\Component\Console\Application::class);
$application->add($container->get(Milkbar\Application\Command\DatabaseMigrationStatus::class));
$application->add($container->get(Milkbar\Application\Command\DatabaseMigrationMigrate::class));
$application->add($container->get(Milkbar\Application\Command\DatabaseMigrationRollback::class));

$application->run();
