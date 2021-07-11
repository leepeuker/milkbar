<?php declare(strict_types=1);

namespace NursingLog\Domain\Session;

use NursingLog\Domain\ValueObject\DateTime;
use NursingLog\Domain\ValueObject\Uuid;

class Entity implements \JsonSerializable
{
    private Uuid $id;

    private DateTime $sessionTime;

    private function __construct(Uuid $id, DateTime $sessionTime)
    {
        $this->id = $id;
        $this->sessionTime = $sessionTime;
    }

    public static function createFromParameters(Uuid $id, DateTime $sessionTime) : self
    {
        return new self($id, $sessionTime);
    }

    public function getId() : Uuid
    {
        return $this->id;
    }

    public function getTime() : DateTime
    {
        return $this->sessionTime;
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'time' => $this->sessionTime->format(DATE_RFC2822),
        ];
    }
}
