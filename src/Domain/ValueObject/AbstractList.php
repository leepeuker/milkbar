<?php declare(strict_types=1);

namespace NursingLog\Domain\ValueObject;

/**
 * @template TValue
 * @implements \IteratorAggregate<int|string, TValue>
 */
abstract class AbstractList implements \Countable, \IteratorAggregate, \JsonSerializable
{
    /** @var array<int|string, TValue> */
    protected $data;

    final protected function __construct()
    {
        $this->data = [];
    }

    public function asArray() : array
    {
        return $this->data;
    }

    public function clear() : void
    {
        $this->data = [];
    }

    public function count() : int
    {
        return count($this->data);
    }

    /**
     * @return \ArrayIterator<int|string, TValue>
     */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }

    public function jsonSerialize() : array
    {
        return $this->data;
    }
}
