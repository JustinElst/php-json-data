<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

final readonly class BeforeElementEvent implements BeforeElementEventInterface
{
    public function __construct(
        private int $index,
        private PathInterface $path,
    ) {
    }

    #[\Override]
    public function getIndex(): int
    {
        return $this->index;
    }

    #[\Override]
    public function getPath(): PathInterface
    {
        return $this->path;
    }

    #[\Override]
    public function with(?PathInterface $path = null, ?int $index = null): BeforeElementEventInterface
    {
        return new self(
            index: $index ?? $this->index,
            path: $path ?? $this->path,
        );
    }
}
