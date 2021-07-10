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

    public function get() : void
    {
        echo json_encode($this->repository->fetchAll(), JSON_THROW_ON_ERROR);
    }

    public function post() : void
    {
        $session = Entity::createFromParameters(Uuid::create(), DateTime::create());

        $this->repository->create($session);

        echo json_encode($session, JSON_THROW_ON_ERROR);
    }
}
