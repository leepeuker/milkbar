<?php declare(strict_types=1);

namespace NursingLog\Application\Repository;

use Doctrine\DBAL\Connection;
use NursingLog\Domain\Session\Entity;
use NursingLog\Domain\Session\EntityList;
use NursingLog\Domain\Session\Repository;
use NursingLog\Domain\ValueObject\DateTime;
use NursingLog\Domain\ValueObject\Uuid;

class Session implements Repository
{
    private Connection $dbConnection;

    public function __construct(Connection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function create(Entity $session) : void
    {
        $this->dbConnection->insert(
            'session',
            [
                'id' => $session->getId(),
                'time' => $session->getTime(),
            ]
        );
    }

    public function fetchAll() : EntityList
    {
        $data = $this->dbConnection->fetchAllAssociative('SELECT * FROM `session`');

        return EntityList::createFromArray($data);
    }
}
