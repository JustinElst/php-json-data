<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

interface BeforeElementEventInterface extends ElementEventInterface
{
    public function with(?PathInterface $path = null, ?int $index = null,): BeforeElementEventInterface;
}
