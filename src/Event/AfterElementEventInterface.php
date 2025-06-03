<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

interface AfterElementEventInterface extends ElementEventInterface
{
    #[\Override]
    public function with(?PathInterface $path = null, ?int $index = null,): AfterElementEventInterface;
}
