<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

interface ElementEventInterface extends EventInterface
{
    public function getIndex(): int;

    public function with(?PathInterface $path = null, ?int $index = null,): ElementEventInterface;
}
