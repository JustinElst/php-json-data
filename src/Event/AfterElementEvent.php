<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

final class AfterElementEvent implements AfterElementEventInterface
{
    public function __construct(
        private int $index,
        private PathInterface $path,
    ) {
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
