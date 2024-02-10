<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Comparator;

use Collator;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;
use Remorhaz\JSON\Data\Value\ValueInterface;

use function is_float;
use function is_int;
use function is_string;

final class GreaterValueComparator implements ComparatorInterface
{
    public function __construct(
        private readonly Collator $collator,
    ) {
    }

    public function compare(ValueInterface $leftValue, ValueInterface $rightValue): bool
    {
        return
            $leftValue instanceof ScalarValueInterface &&
            $rightValue instanceof ScalarValueInterface &&
            $this->isScalarGreater($leftValue, $rightValue);
    }

    private function isScalarGreater(ScalarValueInterface $leftValue, ScalarValueInterface $rightValue): bool
    {
        $leftData = $leftValue->getData();
        $rightData = $rightValue->getData();
        if ((is_int($leftData) || is_float($leftData)) && (is_int($rightData) || is_float($rightData))) {
            return $leftData > $rightData;
        }

        return is_string($leftData) && is_string($rightData) && 1 === $this->collator->compare($leftData, $rightData);
    }
}
