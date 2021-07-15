<?php declare(strict_types=1);

namespace Milkbar\Domain\ValueObject;

class DateTime implements \JsonSerializable
{
    private \DateTime $dateTime;

    private function __construct(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public static function create() : self
    {
        return self::createFromString('now');
    }

    public static function createFromFormat(string $format, string $dateString) : self
    {
        $dateTime = \DateTime::createFromFormat($format, $dateString, new \DateTimeZone('UTC'));

        if ($dateTime === false) {
            throw new \RuntimeException("Could not use format '$format' on: $dateString");
        }

        return new self($dateTime);
    }

    public static function createFromString(string $dateString) : self
    {
        return new self(new \DateTime($dateString, new \DateTimeZone('UTC')));
    }

    public function __toString() : string
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    public function diff(DateTime $dateTime) : DateInterval
    {
         $dateInterval = $this->dateTime->diff(new \DateTime((string)$dateTime));

        return DateInterval::createByDateInterval($dateInterval);
    }

    public function format(string $format) : string
    {
        return $this->dateTime->format($format);
    }

    public function isEqual(DateTime $lastUpdated) : bool
    {
        return (string)$this === (string)$lastUpdated;
    }

    public function jsonSerialize() : string
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }
}
