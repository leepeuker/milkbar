<?php declare(strict_types=1);

namespace NursingLog;

use Twig\Environment;

class Controller
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function get() : void
    {
        echo $this->twig->render('index.html.twig');
    }
}
