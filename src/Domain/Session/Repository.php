<?php declare(strict_types=1);

namespace NursingLog\Domain\Session;

interface Repository
{
    public function create(Entity $session) : void;

    public function fetchAll() : EntityList;
}
