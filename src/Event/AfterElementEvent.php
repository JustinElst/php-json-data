<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

final class AfterElementEvent implements AfterElementEventInterface
{
    public function __construct(
        private readonly int $index,
        private readonly PathInterface $path,
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

    public function with(?PathInterface $path = null, ?int $index = null): AfterElementEventInterface
    {
        return new self(
            index: $index ?? $this->index,
            path: $path ?? $this->path,
        );
    }
}
