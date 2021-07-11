<?php declare(strict_types=1);

namespace NursingLog\Application\Repository;

use Doctrine\DBAL\Connection;
use NursingLog\Domain\Session\Entity;
use NursingLog\Domain\Session\EntityList;
use NursingLog\Domain\Session\Repository;
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
                'id' => (string)$session->getId(),
                'time' => (string)$session->getTime(),
            ]
        );
    }

    public function delete(Uuid $sessionId) : void
    {
        $this->dbConnection->delete(
            'session',
            ['id' => (string)$sessionId,]
        );
    }

    public function fetchAll() : EntityList
    {
        $data = $this->dbConnection->fetchAllAssociative('SELECT * FROM `session` ORDER BY time ASC');

        return EntityList::createFromArray($data);
    }

    public function update(Entity $session) : void
    {
        $this->dbConnection->update(
            'session',
            [
                'time' => (string)$session->getTime(),
                'minutesLeft' => $session->getMinutesLeft(),
                'minutesRight' => $session->getMinutesRight(),
            ],
            [
                'id' => (string)$session->getId(),
            ]
        );
    }
}
