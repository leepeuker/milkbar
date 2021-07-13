<?php declare(strict_types=1);

namespace NursingLog\Domain\Session;

use NursingLog\Domain\ValueObject\AbstractList;
use NursingLog\Domain\ValueObject\DateTime;
use NursingLog\Domain\ValueObject\Uuid;

class EntityList extends AbstractList
{
    public static function createFromArray(array $entitiesData) : self
    {
        $entities = new self();

        foreach ($entitiesData as $entityData) {
            $entities->add(
                Entity::createFromParameters(
                    Uuid::createFromString($entityData['id']),
                    $entityData['user_id'] === null ? null : (int)$entityData['user_id'],
                    DateTime::createFromString($entityData['time']),
                    empty($entityData['minutesLeft']) === true ? null : (int)$entityData['minutesLeft'],
                    empty($entityData['minutesRight']) === true ? null : (int)$entityData['minutesRight'],
                )
            );
        }

        return $entities;
    }

    private function add(Entity $entity) : void
    {
        $this->data[] = $entity;
    }
}
