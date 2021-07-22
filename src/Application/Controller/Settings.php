<?php declare(strict_types=1);

namespace Milkbar\Application\Controller;

use Milkbar\Application\Repository;
use Milkbar\Domain\ValueObject\Request;
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

        $updateSuccessful = false;
        if (isset($_SESSION['updateSuccessful']['timeUntilNextMeal']) === true) {
            $updateSuccessful = $_SESSION['updateSuccessful']['timeUntilNextMeal'];
            unset($_SESSION['updateSuccessful']['timeUntilNextMeal']);
        }

        echo $this->twig->render(
            'settings.html.twig',
            [
                'timeUntilNextMeal' => $this->userRepository->findUserById($_SESSION['user']['id'])->getTimeUntilNextMeal(),
                'updateSuccessful' => [
                    'timeUntilNextMeal' => $updateSuccessful,
                ],
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

        if ($timeUntilNextMeal !== $this->userRepository->findUserById($userId)->getTimeUntilNextMeal()) {
            $this->userRepository->updateTimeUntilNextMeal($userId, $timeUntilNextMeal);
            $_SESSION['user']['timeUntilNextMeal'] = $timeUntilNextMeal;

            $_SESSION['updateSuccessful']['timeUntilNextMeal'] = true;

        }

        header('Location: /settings');
    }
}
