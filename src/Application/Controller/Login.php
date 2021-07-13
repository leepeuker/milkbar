<?php declare(strict_types=1);

namespace NursingLog\Application\Controller;

use Twig\Environment;

class Login
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function get() : void
    {
        echo $this->twig->render('login.html.twig');
    }
}
