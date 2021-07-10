<?php declare(strict_types=1);

namespace NursingLog;

use Doctrine\DBAL;
use NursingLog\Infrastructure\Config;
use Twig;

class Factory
{
    public static function createConfig() : Config
    {
        return Config::createFromFile(__DIR__ . '/../settings/config.ini');
    }

    public static function createDbConnection(Config $config) : DBAL\Connection
    {
        return DBAL\DriverManager::getConnection(
            [
                'dbname' => $config->getAsString('database.name'),
                'user' => $config->getAsString('database.username'),
                'password' => $config->getAsString('database.password'),
                'host' => $config->getAsString('database.host'),
                'driver' => $config->getAsString('database.driver'),
            ]
        );
    }

    public static function createTwigFilesystemLoader() : Twig\Loader\FilesystemLoader
    {
        return new Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
    }
}
