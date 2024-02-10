<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Comparator;

use Collator;
use Iterator;
use Remorhaz\JSON\Data\Value\ArrayValueInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;
use Remorhaz\JSON\Data\Value\ValueInterface;

use function is_string;

final class EqualValueComparator implements ComparatorInterface
{
    public function __construct(
        private readonly Collator $collator,
    ) {
    }

    public function compare(ValueInterface $leftValue, ValueInterface $rightValue): bool
    {
        return match (true) {
            $leftValue instanceof ScalarValueInterface && $rightValue instanceof ScalarValueInterface =>
                $this->isScalarEqual($leftValue, $rightValue),
            $leftValue instanceof ArrayValueInterface && $rightValue instanceof ArrayValueInterface =>
                $this->isArrayEqual($leftValue, $rightValue),
            $leftValue instanceof ObjectValueInterface && $rightValue instanceof ObjectValueInterface =>
                $this->isObjectEqual($leftValue, $rightValue),
            default => false,
        };
    }

    private function isScalarEqual(ScalarValueInterface $leftValue, ScalarValueInterface $rightValue): bool
    {
        $leftData = $leftValue->getData();
        $rightData = $rightValue->getData();

        return is_string($leftData) && is_string($rightData)
            ? 0 === $this->collator->compare($leftData, $rightData)
            : $leftData === $rightData;
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

    /**
     * @param Iterator<string, NodeValueInterface> $valueIterator
     * @return null|array<string, NodeValueInterface>
     */
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
