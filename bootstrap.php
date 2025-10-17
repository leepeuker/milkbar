<?php declare(strict_types=1);

use Milkbar\Factory;

require_once(__DIR__ . '/vendor/autoload.php');

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(
    [
        \Milkbar\Infrastructure\Config::class => DI\factory([Factory::class, 'createConfig']),
        \Psr\Log\LoggerInterface::class => DI\factory([Factory::class, 'createFileLogger']),
        \Twig\Loader\LoaderInterface::class => DI\factory([Factory::class, 'createTwigFilesystemLoader']),
        \Milkbar\Domain\Session\Repository::class => DI\get(\Milkbar\Application\Repository\Session::class),
        \Milkbar\Domain\User\Repository::class => DI\get(\Milkbar\Application\Repository\User::class),
        \Doctrine\DBAL\Connection::class => DI\factory([Factory::class, 'createDbConnection']),
        \Milkbar\Domain\ValueObject\Request::class => DI\factory([Factory::class, 'createCurrentHttpRequest']),
        \Milkbar\Application\Command\DatabaseMigrationStatus::class => DI\factory([Factory::class, 'createDatabaseMigrationStatusCommand']),
        \Milkbar\Application\Command\DatabaseMigrationMigrate::class => DI\factory([Factory::class, 'createDatabaseMigrationMigrateCommand']),
        \Milkbar\Application\Command\DatabaseMigrationRollback::class => DI\factory([Factory::class, 'createDatabaseMigrationRollbackCommand']),
    ]
);

return $builder->build();
