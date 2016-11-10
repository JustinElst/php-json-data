<?php

namespace Remorhaz\JSON\Data\Reference;

use Remorhaz\JSON\Data\SelectorInterface;

class Selector extends Reader implements SelectorInterface
{

    /**
     * @var Cursor
     */
    protected $cursor;

    /**
     * @var Cursor
     */
    protected $parentCursor;

    protected $cursorKey;

    public function __construct(&$data)
    {
        parent::__construct($data);
        $this->selectRoot();
    }

    public function hasData(): bool
    {
        return $this
            ->getCursor()
            ->isBound();
    }

    /**
     * @return $this
     * @see SelectableAccessorInterface
     */
    public function selectRoot()
    {
        $this->cursorKey = null;
        $this->getParentCursor()->unbind();
        $this->getCursor()->bind($this->root);
        return $this;
    }

    public function selectProperty(string $name)
    {
        if (!$this->isObject()) {
            throw new LogicException("Cursor must point an object to select property");
        }
        $parentData = &$this
            ->setCursorKey($name)
            ->getCursor()
            ->getDataReference();
        $this
            ->getParentCursor()
            ->bind($parentData);
        if (property_exists($parentData, $name)) {
            $data = &$parentData->{$name};
            $this
                ->getCursor()
                ->bind($data);
        } else {
            $this
                ->getCursor()
                ->unbind();
            if ('' == $name) {
                // Note: PHP bug #67300 allows some workarounds.
                throw new RuntimeException("Empty string properties are not supported in PHP 7.0");
            }
            if ($this->isNumericProperty($name)) {
                // Numeric properties exported from array can be accessed only through iteration in PHP.
                foreach ($parentData as $existingProperty => &$value) {
                    if ($existingProperty == $name) {
                        $this
                            ->getCursor()
                            ->bind($value);
                        break;
                    }
                }
                unset($value);
            }
        }
        return $this;
    }

    public function isProperty(): bool
    {
        return
            $this->getParentCursor()->isBound() &&
            is_object($this->getParentCursor()->getDataReference()) &&
            $this->isCursorKeySet();
    }

    public function isElement(): bool
    {
        return
            $this->getParentCursor()->isBound() &&
            is_array($this->getParentCursor()->getDataReference()) &&
            $this->isCursorKeySet();
    }

    public function isNewElement(): bool
    {
        return
            $this->getParentCursor()->isBound() &&
            is_array($this->getParentCursor()->getDataReference()) &&
            !$this->isCursorKeySet();
    }

    public function selectElement(int $index)
    {
        if (!$this->isArray()) {
            throw new LogicException("Cursor must point an array to select index");
        }
        $parentData = &$this
            ->setCursorKey($index)
            ->getCursor()
            ->getDataReference();
        $this->getParentCursor()->bind($parentData);
        if (array_key_exists($index, $parentData)) {
            $data = &$parentData[$index];
            $this->getCursor()->bind($data);
        } else {
            $this->getCursor()->unbind();
        }
        return $this;
    }

    public function selectNewElement()
    {
        if (!$this->isArray()) {
            throw new LogicException("Cursor must point an array to select index");
        }
        $this->unsetCursorKey();
        $parentData = &$this->getCursor()->getDataReference();
        $this->getParentCursor()->bind($parentData);
        $this->getCursor()->unbind();
        return $this;
    }

    protected function isNumericProperty(string $key)
    {
        return preg_match('#^-?(?:0|[1-9]\d*)$#', $key) === 1;
    }

    protected function getCursor(): Cursor
    {
        if (null === $this->cursor) {
            $this->cursor = new Cursor();
        }
        return $this->cursor;
    }

    protected function getParentCursor(): Cursor
    {
        if (null === $this->parentCursor) {
            $this->parentCursor = new Cursor();
        }
        return $this->parentCursor;
    }

    protected function isCursorKeySet(): bool
    {
        return null !== $this->cursorKey;
    }

    /**
     * @return string|int
     * @throws LogicException
     */
    protected function getCursorKey()
    {
        if (!$this->isCursorKeySet()) {
            throw new LogicException("Cursor key is not set");
        }
        return $this->cursorKey;
    }

    protected function setCursorKey($key)
    {
        $this->cursorKey = $key;
        return $this;
    }

    protected function unsetCursorKey()
    {
        $this->cursorKey = null;
        return $this;
    }

    protected function getProperty(): string
    {
        if (!$this->isProperty()) {
            throw new LogicException("Property is not selected");
        }
        return $this->getCursorKey();
    }

    protected function getIndex(): int
    {
        if (!$this->isElement()) {
            throw new LogicException("Index is not selected");
        }
        return $this->getCursorKey();
    }

    protected function &getDataReference()
    {
        return $this->getCursor()->getDataReference();
    }

    protected function getDataCopy()
    {
        return $this->getCursor()->getDataCopy();
    }
}
