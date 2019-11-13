<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Comparator;

use Collator;
use Iterator;
use Remorhaz\JSON\Data\Value\ArrayValueInterface;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;
use Remorhaz\JSON\Data\Value\ValueInterface;

final class ContainsValueComparator implements ComparatorInterface
{

    private $equalComparator;

    public function __construct(Collator $collator)
    {
        $this->equalComparator = new EqualValueComparator($collator);
    }

    public function compare(ValueInterface $leftValue, ValueInterface $rightValue): bool
    {
        if ($leftValue instanceof ScalarValueInterface && $rightValue instanceof ScalarValueInterface) {
            return $this->equalComparator->compare($leftValue, $rightValue);
        }
        if ($leftValue instanceof ArrayValueInterface && $rightValue instanceof ArrayValueInterface) {
            return $this->equalComparator->compare($leftValue, $rightValue);
        }
        if ($leftValue instanceof ObjectValueInterface && $rightValue instanceof ObjectValueInterface) {
            return $this->objectContains($leftValue, $rightValue);
        }

        return false;
    }

    private function objectContains(ObjectValueInterface $leftValue, ObjectValueInterface $rightValue): bool
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
        }

        return true;
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
