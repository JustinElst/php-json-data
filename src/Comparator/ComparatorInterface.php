<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Comparator;

use Remorhaz\JSON\Data\Value\ValueInterface;

interface ComparatorInterface
{
    public function compare(ValueInterface $leftValue, ValueInterface $rightValue): bool;
}
