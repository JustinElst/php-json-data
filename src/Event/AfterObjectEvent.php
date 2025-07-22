<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

final class AfterObjectEvent implements AfterObjectEventInterface
{
    public function __construct(
        private readonly PathInterface $path,
    ) {
    }

    #[\Override]
    public function getPath(): PathInterface
    {
        return $this->path;
    }

    #[\Override]
    public function with(?PathInterface $path = null): AfterObjectEventInterface
    {
        return new self(
            path: $path ?? $this->path,
        );
    }
}
