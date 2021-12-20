<?php declare(strict_types=1);

namespace Milkbar\Domain\Session;

use Milkbar\Domain\ValueObject\DateTime;
use Milkbar\Domain\ValueObject\Uuid;

interface Repository
{
    public function create(Entity $session) : void;

    public function delete(Uuid $sessionId) : void;

    public function fetchAllInTimeframeByUserId(int $hours, int $userId) : EntityList;

    public function fetchCountByDatesAndUserId(DateTime $startDate, DateTime $endDate, int $userId) : int;

    public function update(Entity $session) : void;
}
