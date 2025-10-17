<?php declare(strict_types=1);

/** @var DI\Container $container */
$container = require(__DIR__ . '/../bootstrap.php');

$config = $container->get(\Milkbar\Infrastructure\Config::class);

return [
    'paths' => [
        'migrations' => __DIR__ . '/../db/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'dynamic',
        'dynamic' => [
            'adapter' => 'mysql',
            'host' => $config->getAsString('DATABASE_MYSQL_HOST'),
            'name' => $config->getAsString('DATABASE_MYSQL_NAME'),
            'user' => $config->getAsString('DATABASE_MYSQL_USER'),
            'pass' => $config->getAsString('DATABASE_MYSQL_PASSWORD'),
            'port' => 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8_unicode_ci',
        ],
    ],
];
