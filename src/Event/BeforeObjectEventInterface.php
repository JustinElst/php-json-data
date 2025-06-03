<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

interface BeforeObjectEventInterface extends EventInterface
{
    #[\Override]
    public function with(?PathInterface $path = null): BeforeObjectEventInterface;
}
