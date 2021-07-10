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
                    DateTime::createFromString($entityData['time'])
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
