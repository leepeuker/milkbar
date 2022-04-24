<?php declare(strict_types=1);

namespace Milkbar;

use Doctrine\DBAL;
use Milkbar\Domain\ValueObject\Request;
use Milkbar\Infrastructure\Config;
use Twig;

class Factory
{
    public static function createConfig() : Config
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        return Config::createFromEnv();
    }

    public static function createCurrentHttpRequest() : Request
    {
        return Request::createFromGlobals();
    }

    public static function createDbConnection(Config $config) : DBAL\Connection
    {
        return DBAL\DriverManager::getConnection(
            [
                'dbname' => $config->getAsString('DB_NAME'),
                'user' => $config->getAsString('DB_USER'),
                'password' => $config->getAsString('DB_PASSWORD'),
                'host' => $config->getAsString('DB_HOST'),
                'driver' => $config->getAsString('DB_DRIVER'),
            ]
        );
    }

    public static function createTwigFilesystemLoader() : Twig\Loader\FilesystemLoader
    {
        return new Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
    }
}
