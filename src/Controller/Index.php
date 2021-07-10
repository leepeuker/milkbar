<?php declare(strict_types=1);

namespace NursingLog\Controller;

use Twig\Environment;

class Index
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
