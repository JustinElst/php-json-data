<?php

namespace Remorhaz\JSON\Data;

class RawSelectableWriter extends RawSelectableReader implements SelectableWriterInterface
{


    public function replaceData(ReaderInterface $source)
    {
        if (!$this->hasData()) {
            throw new LogicException("No data to get replaced");
        }
        $targetData = &$this
            ->getCursor()
            ->getDataReference();
        $targetData = $source->getData();
        return $this;
    }


    public function insertProperty(ReaderInterface $source)
    {
        if (!$this->isPropertySelected()) {
            throw new LogicException("Property must be selected to insert data");
        }
        if ($this->hasData()) {
            throw new LogicException("Property already exists");
        }
        $sourceData = $source->getData();
        $parentData = &$this
            ->getParentCursor()
            ->getDataReference();
        $parentData->{$this->getProperty()} = &$sourceData;
        $this->getCursor()->bind($sourceData);
        return $this;
    }


    public function appendElement(ReaderInterface $source)
    {
        if (!$this->isNewIndexSelected()) {
            throw new LogicException("New index must be selected to insert data");
        }
        if ($this->hasData()) {
            throw new LogicException("Element already exists");
        }
        $sourceData = $source->getData();
        $parentData = &$this
            ->getParentCursor()
            ->getDataReference();
        $parentData[] = &$sourceData;
        array_splice($parentData, 0, 0); // Rebuilding array indices.
        $this
            ->getCursor()
            ->unbind();
        return $this;
    }


    /**
     * @param ReaderInterface $source
     * @return $this
     */
    public function insertElement(ReaderInterface $source)
    {
        if (!$this->isIndexSelected()) {
            throw new LogicException("Index must be selected before element insertion");
        }
        if (!$this->hasData()) {
            throw new LogicException("Following element must be selected before insertion");
        }
        $parentData = &$this
            ->getParentCursor()
            ->getDataReference();
        $index = $this->getIndex();
        array_splice($parentData, $index, 0, [$source->getData()]);
        $this
            ->getCursor()
            ->bind($parentData[$index]);
        return $this;
    }


    /**
     * @return $this
     * @see AccessorInterface
     */
    public function removeProperty()
    {
        if (!$this->isPropertySelected()) {
            throw new LogicException("Property must be selected to be removed");
        }
        $property = $this->getProperty();
        if (!$this->hasData()) {
            throw new LogicException("Property '{$property}' doesn't have data");
        }
        $parentData = &$this
            ->getParentCursor()
            ->getDataReference();
        if (property_exists($parentData, $property)) {
            unset($parentData->{$property});
        } else {
            if ($this->isNumericProperty($property)) {
                // Numeric properties exported from array can be accessed only through iteration in PHP.
                $newParentData = new \stdClass;
                foreach ($parentData as $existingProperty => &$value) {
                    if ($existingProperty != $property) {
                        $newParentData->{$existingProperty} = &$value;
                    }
                }
                unset($value);
                $parentData = $newParentData;
            }
        }
        $this->getCursor()->unbind();
        return $this;
    }


    public function removeElement()
    {
        if (!$this->isIndexSelected()) {
            throw new LogicException("Index must be selected to remove element");
        }
        $index = $this->getIndex();
        if (!$this->hasData()) {
            throw new LogicException("Element #{$index} doesn't have data");
        }
        $parentData = &$this
            ->getParentCursor()
            ->getDataReference();
        array_splice($parentData, $index, 1);
        $this
            ->unsetCursorKey()
            ->getCursor()
            ->unbind();
        return $this;
    }
}
