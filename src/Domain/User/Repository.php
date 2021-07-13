<?php declare(strict_types=1);

namespace NursingLog\Domain\User;

interface Repository
{
    public function findUserByEmail(string $email) : ?Entity;
}
