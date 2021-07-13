<?php declare(strict_types=1);

namespace NursingLog\Application\Service\User\Exception;

class InvalidCredentials extends \RuntimeException
{
    public static function create(string $email) : self
    {
        return new self('Provided invalid credentials for email: ' . $email);
    }
}
