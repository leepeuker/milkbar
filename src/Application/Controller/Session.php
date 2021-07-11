<?php declare(strict_types=1);

namespace NursingLog\Application\Controller;

use NursingLog\Domain\Session\Entity;
use NursingLog\Domain\Session\Repository;
use NursingLog\Domain\ValueObject\DateTime;
use NursingLog\Domain\ValueObject\Uuid;

class Session
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function delete(array $routeParameters) : void
    {
        $this->repository->delete(Uuid::createFromString($routeParameters['id']));
    }

    public function get() : void
    {
        echo json_encode($this->repository->fetchAll(), JSON_THROW_ON_ERROR);
    }

    public function post() : void
    {
        $session = Entity::createFromParameters(Uuid::create(), DateTime::create(), null, null);

        $this->repository->create($session);

        echo json_encode($session, JSON_THROW_ON_ERROR);
    }

    public function put(array $routeParameters) : void
    {
        $body = (string)file_get_contents('php://input');
        $requestData = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        $this->repository->update(
            Entity::createFromParameters(
                Uuid::createFromString($routeParameters['id']),
                DateTime::createFromString($requestData['time']),
                empty($requestData['minutesLeft']) === true ? null : (int)$requestData['minutesLeft'],
                empty($requestData['minutesRight']) === true ? null : (int)$requestData['minutesRight'],
            )
        );
    }
}
