<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Comparator;

use Collator;
use Iterator;
use function is_string;
use Remorhaz\JSON\Data\Value\ArrayValueInterface;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;
use Remorhaz\JSON\Data\Value\ValueInterface;

final class EqualValueComparator implements ComparatorInterface
{

    private $collator;

    public function __construct(Collator $collator)
    {
        $this->collator = $collator;
    }

    public function compare(ValueInterface $leftValue, ValueInterface $rightValue): bool
    {
        if ($leftValue instanceof ScalarValueInterface && $rightValue instanceof ScalarValueInterface) {
            return $this->isScalarEqual($leftValue, $rightValue);
        }

        if ($leftValue instanceof ArrayValueInterface && $rightValue instanceof ArrayValueInterface) {
            return $this->isArrayEqual($leftValue, $rightValue);
        }

        if ($leftValue instanceof ObjectValueInterface && $rightValue instanceof ObjectValueInterface) {
            return $this->isObjectEqual($leftValue, $rightValue);
        }

        return false;
    }

    private function isScalarEqual(ScalarValueInterface $leftValue, ScalarValueInterface $rightValue): bool
    {
        $leftData = $leftValue->getData();
        $rightData = $rightValue->getData();

        if (is_string($leftData) && is_string($rightData)) {
            return 0 === $this->collator->compare($leftData, $rightData);
        }

        return $leftData === $rightData;
    }

    private function isArrayEqual(ArrayValueInterface $leftValue, ArrayValueInterface $rightValue): bool
    {
        $leftValueIterator = $leftValue->createChildIterator();
        $rightValueIterator = $rightValue->createChildIterator();

        while ($leftValueIterator->valid()) {
            if (!$rightValueIterator->valid()) {
                return false;
            }
            if (!$this->compare($leftValueIterator->current(), $rightValueIterator->current())) {
                return false;
            }
            $leftValueIterator->next();
            $rightValueIterator->next();
        }

        return !$rightValueIterator->valid();
    }

    private function isObjectEqual(ObjectValueInterface $leftValue, ObjectValueInterface $rightValue): bool
    {
        $leftProperties = $this->getPropertiesWithoutDuplicates($leftValue->createChildIterator());
        if (!isset($leftProperties)) {
            return false;
        }
        $rightProperties = $this->getPropertiesWithoutDuplicates($rightValue->createChildIterator());
        if (!isset($rightProperties)) {
            return false;
        }
        foreach ($rightProperties as $property => $rightValue) {
            if (!isset($leftProperties[$property])) {
                return false;
            }
            if (!$this->compare($leftProperties[$property], $rightValue)) {
                return false;
            }
            unset($leftProperties[$property]);
        }

        return empty($leftProperties);
    }

    private function getPropertiesWithoutDuplicates(Iterator $valueIterator): ?array
    {
        $valuesByProperty = [];
        while ($valueIterator->valid()) {
            $property = $valueIterator->key();
            if (isset($valuesByProperty[$property])) {
                return null;
            }
            $valuesByProperty[$property] = $valueIterator->current();
            $valueIterator->next();
        }

        return $valuesByProperty;
    }
}
