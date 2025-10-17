<?php declare(strict_types=1);

namespace Milkbar\Infrastructure;

class Config
{
    private function __construct(private readonly array $data)
    {
    }

    public static function createFromArray(array $data) : self
    {
        return new self($data);
    }

    public function getAsArray(string $parameter) : array
    {
        return (array)$this->get($parameter);
    }

    public function getAsBool(string $parameter) : bool
    {
        return (bool)$this->get($parameter);
    }

    public function getAsFloat(string $parameter) : float
    {
        return (float)$this->get($parameter);
    }

    public function getAsInt(string $parameter) : int
    {
        return (int)$this->get($parameter);
    }

    public function getAsString(string $parameter) : string
    {
        return (string)$this->get($parameter);
    }

    private function ensureKeyExists(string $key) : void
    {
        if (isset($this->data[$key]) === false) {
            throw new \OutOfBoundsException('Key does not exist: ' . $key);
        }
    }

    private function get(string $key) : mixed
    {
        $this->ensureKeyExists($key);

        return $this->data[$key];
    }
}
