<?php declare(strict_types=1);

use NursingLog\Factory;

require_once(__DIR__ . '/vendor/autoload.php');

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(
    [
        \NursingLog\Infrastructure\Config::class => DI\factory([Factory::class, 'createConfig']),
        \Psr\Log\LoggerInterface::class => DI\factory([Factory::class, 'createFileLogger']),
        \Twig\Loader\LoaderInterface::class => DI\factory([Factory::class, 'createTwigFilesystemLoader']),
        \NursingLog\Domain\Session\Repository::class => DI\get(\NursingLog\Application\Repository\Session::class),
        \Doctrine\DBAL\Connection::class => DI\factory([Factory::class, 'createDbConnection']),
        \NursingLog\Domain\ValueObject\Request::class => DI\factory([Factory::class, 'createCurrentHttpRequest']),
    ]
);

return $builder->build();
