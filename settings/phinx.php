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
        $config->getAsString('ENV') => [
            'adapter' => $config->getAsString('DB_DRIVER') === 'pdo_mysql' ? 'mysql' : $config->getAsString('DB.driver'),
            'host' => $config->getAsString('DB_HOST'),
            'name' => $config->getAsString('DB_NAME'),
            'user' => $config->getAsString('DB_USER'),
            'pass' => $config->getAsString('DB_PASSWORD'),
            'port' => 3306,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ],
];
