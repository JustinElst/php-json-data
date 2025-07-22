<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

interface AfterArrayEventInterface extends EventInterface
{
    #[\Override]
    public function with(?PathInterface $path = null): AfterArrayEventInterface;
}
