<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

final readonly class BeforeObjectEvent implements BeforeObjectEventInterface
{
    public function __construct(
        private PathInterface $path,
    ) {
    }

    #[\Override]
    public function getPath(): PathInterface
    {
        return $this->path;
    }

    #[\Override]
    public function with(?PathInterface $path = null): BeforeObjectEventInterface
    {
        return new self(
            path: $path ?? $this->path,
        );
    }
}
