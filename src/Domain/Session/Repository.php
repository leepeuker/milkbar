<?php declare(strict_types=1);

namespace Milkbar\Domain\Session;

use Milkbar\Domain\ValueObject\Uuid;

interface Repository
{
    public function create(Entity $session) : void;

    public function delete(Uuid $sessionId) : void;

    public function fetchAllInTimeframe(int $hours, int $userId) : EntityList;

    public function update(Entity $session) : void;
}
