<?php

namespace Remorhaz\JSON\Data\Reference;

use Remorhaz\JSON\Data\ReaderInterface;

class Reader implements ReaderInterface
{

    /**
     * Link to the root of raw PHP data structure.
     *
     * @var mixed
     */
    protected $root;

    public function __construct(&$data)
    {
        $this->root = &$data;
    }

    public function getAsReader(): ReaderInterface
    {
        return new self($this->getDataCopy());
    }

    public function hasData(): bool
    {
        return true;
    }

    public function getAsStruct()
    {
        return $this->getDataCopy();
    }

    public function getAsString(): string
    {
        if (!$this->isString()) {
            throw new LogicException("Current value is not a string");
        }
        return (string) $this->getDataCopy();
    }

    public function isObject(): bool
    {
        return is_object($this->getDataReference());
    }

    public function isArray(): bool
    {
        return is_array($this->getDataReference());
    }

    public function isString(): bool
    {
        return is_string($this->getDataReference());
    }

    public function isNumber(): bool
    {
        $data = &$this->getDataReference();
        return is_int($data) || is_float($data);
    }

    public function isBoolean(): bool
    {
        return is_bool($this->getDataReference());
    }

    public function isNull(): bool
    {
        return is_null($this->getDataReference());
    }

    public function getElementCount(): int
    {
        if (!$this->isArray()) {
            throw new LogicException("Cursor should point an array to get elements count");
        }
        return count($this->getDataReference());
    }

    public function test(ReaderInterface $value)
    {
        if ($this->getAsStruct() !== $value->getAsStruct()) {
            throw new RuntimeException("Given value is not equal to current one");
        }
        return $this;
    }

    protected function &getRoot()
    {
        return $this->root;
    }

    protected function &getDataReference()
    {
        return $this->getRoot();
    }

    protected function getDataCopy()
    {
        return $this->isObject() ? clone $this->getRoot() : $this->getRoot();
    }
}
