<?php declare(strict_types=1);

namespace Milkbar\Application\Repository;

use Doctrine\DBAL\Connection;
use Milkbar\Domain\Session\Entity;
use Milkbar\Domain\Session\EntityList;
use Milkbar\Domain\Session\Repository;
use Milkbar\Domain\ValueObject\DateTime;
use Milkbar\Domain\ValueObject\Uuid;

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
                'user_id' => $session->getUserId() === null ? null : (string)$session->getUserId(),
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

    public function fetchAllInTimeframeByUserId(int $hours, int $userId) : EntityList
    {
        $data = $this->dbConnection->fetchAllAssociative(
            'SELECT * FROM `session` WHERE HOUR(TIMEDIFF(NOW(), time)) < ? AND user_id = ? ORDER BY time ASC',
            [
                $hours,
                $userId,
            ]
        );

        return EntityList::createFromArray($data);
    }

    public function fetchCountByDatesAndUserId(DateTime $startDate, DateTime $endDate, int $userId) : int
    {
        $data = $this->dbConnection->fetchOne(
            'SELECT COUNT(id) AS count 
            FROM session 
            WHERE user_id = ? 
              AND time > ? 
              AND time < ?;',
            [
                $userId,
                (string)$startDate,
                (string)$endDate,
            ]
        );

        return (int)$data;
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
