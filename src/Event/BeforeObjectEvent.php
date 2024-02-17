<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

final class BeforeObjectEvent implements BeforeObjectEventInterface
{
    public function __construct(
        private readonly PathInterface $path,
    ) {
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }

    public function with(?PathInterface $path = null): BeforeObjectEventInterface
    {
        return new self(
            path: $path ?? $this->path,
        );
    }
}
