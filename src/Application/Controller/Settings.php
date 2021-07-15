<?php declare(strict_types=1);

namespace NursingLog\Application\Controller;

use NursingLog\Application\Repository;
use NursingLog\Domain\ValueObject\Request;
use Twig\Environment;

class Settings
{
    private Environment $twig;

    private Repository\User $userRepository;

    public function __construct(Environment $twig, Repository\User $userRepository)
    {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }

    public function renderPage() : void
    {
        if (isset($_SESSION['user']) === false) {
            header('Location: /');
        }

        echo $this->twig->render(
            'settings.html.twig',
            [
                'timeUntilNextMeal' => $this->userRepository->findUserById($_SESSION['user']['id'])->getTimeUntilNextMeal(),
            ]
        );
    }

    public function update(Request $request) : void
    {
        if (isset($_SESSION['user']) === false) {
            header('Location: /');
        }

        $userId = (int)$_SESSION['user']['id'];
        $timeUntilNextMeal = (int)$request->getPostParameters()['timeUntilNextMeal'];

        $this->userRepository->updateTimeUntilNextMeal($userId, $timeUntilNextMeal);

        header('Location: /settings');
    }
}
