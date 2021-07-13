<?php declare(strict_types=1);

namespace NursingLog\Domain\Session;

use NursingLog\Domain\ValueObject\DateTime;
use NursingLog\Domain\ValueObject\Uuid;

class Entity implements \JsonSerializable
{
    private Uuid $id;

    private ?int $minutesLeft;

    private ?int $minutesRight;

    private DateTime $sessionTime;

    private ?int $userId;

    private function __construct(Uuid $id, ?int $userId, DateTime $sessionTime, ?int $minutesLeft, ?int $minutesRight)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->sessionTime = $sessionTime;
        $this->minutesLeft = $minutesLeft;
        $this->minutesRight = $minutesRight;
    }

    public static function createFromParameters(Uuid $id, ?int $userId, DateTime $sessionTime, ?int $minutesLeft, ?int $minutesRight) : self
    {
        return new self($id, $userId, $sessionTime, $minutesLeft, $minutesRight);
    }

    public function getId() : Uuid
    {
        return $this->id;
    }

    public function getMinutesLeft() : ?int
    {
        return $this->minutesLeft;
    }

    public function getMinutesRight() : ?int
    {
        return $this->minutesRight;
    }

    public function getTime() : DateTime
    {
        return $this->sessionTime;
    }

    public function getUserId() : ?int
    {
        return $this->userId;
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'time' => $this->sessionTime->format(DATE_RFC2822),
            'minutesLeft' => $this->minutesLeft,
            'minutesRight' => $this->minutesRight,
        ];
    }
}
