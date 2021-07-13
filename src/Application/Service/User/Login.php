<?php declare(strict_types=1);

namespace NursingLog\Application\Service\User;

use NursingLog\Application\Service\User\Exception\InvalidCredentials;
use NursingLog\Domain\User\Repository;

class Login
{
    private Repository $userRepository;

    public function __construct(Repository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate(string $email, string $password) : void
    {
        $user = $this->userRepository->findUserByEmail($email);

        if ($user === null || password_verify($password, $user->getPasswordHash()) === false) {
            throw InvalidCredentials::create($email);
        }

        session_regenerate_id();

        $_SESSION['user']['id'] = $user->getId();
    }
}
