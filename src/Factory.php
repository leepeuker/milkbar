<?php declare(strict_types=1);

namespace Milkbar;

use Doctrine\DBAL;
use Milkbar\Application\Command;
use Milkbar\Domain\ValueObject\Request;
use Milkbar\Infrastructure\Config;
use Phinx\Console\PhinxApplication;
use Psr\Container\ContainerInterface;
use Twig;

class Factory
{
    public static function createConfig() : Config
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $fpmEnvironment = $_ENV;
        $systemEnvironment = getenv();

        return Config::createFromArray(array_merge($fpmEnvironment, $systemEnvironment));
    }

    public static function createCurrentHttpRequest() : Request
    {
        return Request::createFromGlobals();
    }

    public static function createDbConnection(Config $config) : DBAL\Connection
    {
        return DBAL\DriverManager::getConnection(
            [
                'dbname' => $config->getAsString('DATABASE_MYSQL_NAME'),
                'user' => $config->getAsString('DATABASE_MYSQL_USER'),
                'password' => $config->getAsString('DATABASE_MYSQL_PASSWORD'),
                'host' => $config->getAsString('DATABASE_MYSQL_HOST'),
                'charset' => $config->getAsString('DATABASE_MYSQL_CHARSET'),
                'driver' => 'pdo_mysql',
            ]
        );
    }

    public static function createTwigFilesystemLoader() : Twig\Loader\FilesystemLoader
    {
        return new Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
    }

    public static function createDatabaseMigrationMigrateCommand(ContainerInterface $container) : Command\DatabaseMigrationMigrate
    {
        return new Command\DatabaseMigrationMigrate(
            $container->get(PhinxApplication::class),
            __DIR__ . '/../settings/phinx.php',
        );
    }

    public static function createDatabaseMigrationRollbackCommand(ContainerInterface $container) : Command\DatabaseMigrationRollback
    {
        return new Command\DatabaseMigrationRollback(
            $container->get(PhinxApplication::class),
            __DIR__ . '/../settings/phinx.php',
        );
    }

    public static function createDatabaseMigrationStatusCommand(ContainerInterface $container) : Command\DatabaseMigrationStatus
    {
        return new Command\DatabaseMigrationStatus(
            $container->get(PhinxApplication::class),
            __DIR__ . '/../settings/phinx.php',
        );
    }
}
