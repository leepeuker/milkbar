<?php declare(strict_types=1);

namespace Milkbar\Application\Controller;

use Milkbar\Application\Repository;
use Milkbar\Domain\ValueObject\DateTime;
use Twig\Environment;

class Stats
{
    private Repository\Session $sessionRepository;

    private Environment $twig;

    public function __construct(Environment $twig, Repository\Session $sessionRepository)
    {
        $this->twig = $twig;
        $this->sessionRepository = $sessionRepository;
    }

    public function renderPage() : void
    {
        if (isset($_SESSION['user']) === false) {
            header('Location: /');
        }

        $userId = (int)$_SESSION['user']['id'];

        echo $this->twig->render(
            'stats.html.twig',
            [
                'firstSessionDate' => $this->sessionRepository->findFirstSessionDate($userId)?->format('d.m.Y'),
            ]
        );
    }
}
