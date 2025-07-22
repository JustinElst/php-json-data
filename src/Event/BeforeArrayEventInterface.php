<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

interface BeforeArrayEventInterface extends EventInterface
{
    #[\Override]
    public function with(?PathInterface $path = null): BeforeArrayEventInterface;
}
