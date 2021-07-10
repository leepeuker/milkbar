<?php declare(strict_types=1);

namespace NursingLog;

use Twig;

class Factory
{
    public static function createTwigFilesystemLoader() : Twig\Loader\FilesystemLoader
    {
        return new Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
    }
}
