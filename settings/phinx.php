<?php declare(strict_types=1);

/** @var DI\Container $container */
$container = require(__DIR__ . '/../bootstrap.php');

$config = $container->get(\NursingLog\Infrastructure\Config::class);

return [
    'paths' => [
        'migrations' => __DIR__ . '/../db/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'development' => [
            'adapter' => 'mysql',
            'host' => $config->getAsString('database.host'),
            'name' => $config->getAsString('database.name'),
            'user' => $config->getAsString('database.username'),
            'pass' => $config->getAsString('database.password'),
            'port' => 3306,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ],
];
