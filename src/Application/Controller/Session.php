<?php declare(strict_types=1);

namespace Milkbar\Application\Controller;

use Milkbar\Domain\Session\Entity;
use Milkbar\Domain\Session\Repository;
use Milkbar\Domain\ValueObject\DateTime;
use Milkbar\Domain\ValueObject\Request;
use Milkbar\Domain\ValueObject\Uuid;

class Session
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function delete(Request $request) : void
    {
        $this->repository->delete(Uuid::createFromString($request->getRouteParameters()['id']));
    }

    public function get(Request $request) : void
    {
        $maxAge = (int)$request->getGetParameters()['maxAge'];

        echo json_encode($this->repository->fetchAllInTimeframeByUserId($maxAge, $_SESSION['user']['id']), JSON_THROW_ON_ERROR);
    }

    public function getCount(Request $request) : void
    {
        $userId = (int)$_SESSION['user']['id'];
        $getParameters = $request->getGetParameters();
        $startDate = DateTime::createFromString($getParameters['startDate']);
        $endDate = DateTime::createFromString($getParameters['endDate'] . ' 23:59:59');

        echo $this->repository->fetchCountByDatesAndUserId($startDate, $endDate, $userId);
    }

    public function post() : void
    {
        $session = Entity::createFromParameters(
            Uuid::create(),
            $_SESSION['user']['id'],
            DateTime::create(),
            null,
            null
        );

        $this->repository->create($session);

        echo json_encode($session, JSON_THROW_ON_ERROR);
    }

    public function put(Request $request) : void
    {
        $requestData = json_decode($request->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->repository->update(
            Entity::createFromParameters(
                Uuid::createFromString($request->getRouteParameters()['id']),
                $_SESSION['user']['id'],
                DateTime::createFromString($requestData['time']),
                empty($requestData['minutesLeft']) === true ? null : (int)$requestData['minutesLeft'],
                empty($requestData['minutesRight']) === true ? null : (int)$requestData['minutesRight'],
            )
        );
    }
}
