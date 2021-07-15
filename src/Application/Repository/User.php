<?php declare(strict_types=1);

namespace NursingLog\Application\Repository;

use Doctrine\DBAL\Connection;
use NursingLog\Domain\User\Entity;
use NursingLog\Domain\User\Repository;

class User implements Repository
{
    private Connection $dbConnection;

    public function __construct(Connection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function findUserByEmail(string $email) : ?Entity
    {
        $data = $this->dbConnection->fetchAllAssociative('SELECT * FROM `user` WHERE `email` = ?', [$email]);

        if (count($data) === 0) {
            return null;
        }

        return Entity::createFromArray($data[0]);
    }

    public function findUserById(int $userId) : ?Entity
    {
        $data = $this->dbConnection->fetchAllAssociative('SELECT * FROM `user` WHERE `id` = ?', [$userId]);

        if (count($data) === 0) {
            return null;
        }

        return Entity::createFromArray($data[0]);
    }

    public function updateTimeUntilNextMeal(int $userId, int $timeUntilNextMeal) : void
    {
        $this->dbConnection->update(
            'user',
            [
                'timeUntilNextMeal' => $timeUntilNextMeal,
            ],
            [
                'id' => $userId,
            ]
        );
    }
}
