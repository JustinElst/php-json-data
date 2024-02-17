<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

interface ScalarEventInterface extends EventInterface
{
    public function getData(): int|string|float|bool|null;

    public function with(
        ?PathInterface $path = null,
        int|string|float|bool|null $data = null,
        bool $forceData = false,
    ): ScalarEventInterface;
}
