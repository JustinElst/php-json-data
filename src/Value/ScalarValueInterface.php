<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value;

interface ScalarValueInterface extends ValueInterface, DataAwareInterface
{
    public function getData(): int|float|string|bool|null;
}
