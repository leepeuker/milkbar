<?php declare(strict_types=1);

use NursingLog\Factory;

require_once(__DIR__ . '/vendor/autoload.php');

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(
    [
        \Psr\Log\LoggerInterface::class => DI\factory([Factory::class, 'createFileLogger']),
        \Twig\Loader\LoaderInterface::class => DI\factory([Factory::class, 'createTwigFilesystemLoader']),
    ]
);

return $builder->build();
