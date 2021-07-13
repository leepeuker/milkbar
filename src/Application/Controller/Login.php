<?php declare(strict_types=1);

namespace NursingLog\Application\Controller;

use NursingLog\Domain\ValueObject\Request;
use NursingLog\Application\Service;
use Twig\Environment;

class Login
{
    private Environment $twig;

    private Service\User\Login $userLoginService;

    public function __construct(Environment $twig, Service\User\Login $userLoginService)
    {
        $this->twig = $twig;
        $this->userLoginService = $userLoginService;
    }

    public function authenticate(Request $request) : void
    {
        if (isset($_SESSION['user']) === true) {
            header('Location: /dashboard');
        }

        try {
            $this->userLoginService->authenticate(
                $request->getPostParameters()['email'],
                $request->getPostParameters()['password']
            );
        } catch (Service\User\Exception\InvalidCredentials $e) {
            $_SESSION['failedLogin'] = true;
        }

        header('Location: /dashboard');
    }

    public function destroy() : void
    {
        unset($_SESSION['user']);
        session_regenerate_id();

        header('Location: /');
    }

    public function get() : void
    {
        if (isset($_SESSION['user']) === true) {
            header('Location: /dashboard');
        }

        echo $this->twig->render('login.html.twig', ['failedLogin' => isset($_SESSION['failedLogin'])]);

        unset($_SESSION['failedLogin']);
    }
}
