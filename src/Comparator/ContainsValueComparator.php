<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Comparator;

use Collator;
use Remorhaz\JSON\Data\Value\ArrayValueInterface;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;
use Remorhaz\JSON\Data\Value\ValueInterface;

final class ContainsValueComparator implements ComparatorInterface
{

    private $collator;

    private $equalComparator;

    public function __construct(Collator $collator)
    {
        $this->collator = $collator;
        $this->equalComparator = new EqualValueComparator($this->collator);
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
        $leftValueIterator = $leftValue->createChildIterator();
        $rightValueIterator = $rightValue->createChildIterator();
        $valuesByProperty = [];
        while ($leftValueIterator->valid()) {
            $property = $leftValueIterator->key();
            if (isset($valuesByProperty[$property])) {
                return false;
            }
            $valuesByProperty[$property] = $leftValueIterator->current();
            $leftValueIterator->next();
        }
        while ($rightValueIterator->valid()) {
            $property = $rightValueIterator->key();
            if (!isset($valuesByProperty[$property])) {
                return false;
            }
            if (!$this->compare($valuesByProperty[$property], $rightValueIterator->current())) {
                return false;
            }
            $rightValueIterator->next();
        }

        return true;
    }
}
