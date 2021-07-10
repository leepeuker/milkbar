<?php declare(strict_types=1);

namespace NursingLog\Domain\ValueObject;

use Ramsey\Uuid\UuidInterface;

class Uuid implements \JsonSerializable
{
    public const VALID_PATTERN_WITHOUT_ANCHORS = '[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}';

    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function create() : self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid4());
    }

    public static function createFromString(string $uuid) : self
    {
        return new self(\Ramsey\Uuid\Uuid::fromString($uuid));
    }

    public function __toString() : string
    {
        return $this->uuid->toString();
    }

    public function isEqual(Uuid $uuid) : bool
    {
        return (string)$this === (string)$uuid;
    }

    public function jsonSerialize() : string
    {
        return $this->uuid->toString();
    }
}
