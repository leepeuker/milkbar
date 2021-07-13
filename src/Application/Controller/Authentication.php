<?php declare(strict_types=1);

namespace NursingLog\Application\Controller;

use NursingLog\Application\Service\User;
use NursingLog\Domain\ValueObject\Request;
use Twig\Environment;

class Authentication
{
    private Environment $twig;

    private User\Login $userLoginService;

    public function __construct(Environment $twig, User\Login $userLoginService)
    {
        $this->twig = $twig;
        $this->userLoginService = $userLoginService;
    }

    public function login(Request $request) : void
    {
        if (isset($_SESSION['user']) === true) {
            header('Location: /dashboard');
        }

        try {
            $this->userLoginService->authenticate(
                $request->getPostParameters()['email'],
                $request->getPostParameters()['password'],
                isset($request->getPostParameters()['rememberMe']) === true
            );
        } catch (User\Exception\InvalidCredentials $e) {
            $_SESSION['failedLogin'] = true;
        }

        header('Location: /dashboard');
    }

    public function logout() : void
    {
        unset($_SESSION['user']);
        session_regenerate_id();

        header('Location: /');
    }

    public function renderLoginPage() : void
    {
        if (isset($_SESSION['user']) === true) {
            header('Location: /dashboard');
        }

        echo $this->twig->render('login.html.twig', ['failedLogin' => isset($_SESSION['failedLogin'])]);

        unset($_SESSION['failedLogin']);
    }
}
