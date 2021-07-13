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

    public function authenticate(string $email, string $password, bool $rememberMe) : void
    {
        $user = $this->userRepository->findUserByEmail($email);

        if ($user === null || password_verify($password, $user->getPasswordHash()) === false) {
            throw InvalidCredentials::create($email);
        }

        if ($rememberMe === true) {
            session_destroy();
            ini_set('session.cookie_lifetime', '2419200');
            ini_set('session.gc_maxlifetime', '2419200');
            session_start(
                [
                    'cookie_lifetime' => 2419200,
                ]
            );
        }

        session_regenerate_id();

        $_SESSION['user']['id'] = $user->getId();
    }
}
