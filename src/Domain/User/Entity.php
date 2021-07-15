<?php declare(strict_types=1);

namespace NursingLog\Domain\User;

class Entity
{
    private string $email;

    private int $id;

    private string $passwordHash;

    private int $timeUntilNextMeal;

    private function __construct(int $id, string $email, string $passwordHash, int $timeUntilNextMeal)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->timeUntilNextMeal = $timeUntilNextMeal;
    }

    public static function createFromArray(array $data) : self
    {
        return new self(
            (int)$data['id'],
            $data['email'],
            $data['password'],
            (int)$data['timeUntilNextMeal'],
        );
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getPasswordHash() : string
    {
        return $this->passwordHash;
    }

    public function getTimeUntilNextMeal() : int
    {
        return $this->timeUntilNextMeal;
    }
}
