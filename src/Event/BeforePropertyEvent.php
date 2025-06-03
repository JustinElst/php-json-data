<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

final readonly class BeforePropertyEvent implements BeforePropertyEventInterface
{
    public function __construct(
        private string $name,
        private PathInterface $path,
    ) {
    }

    #[\Override]
    public function getName(): string
    {
        return $this->name;
    }

    #[\Override]
    public function getPath(): PathInterface
    {
        return $this->path;
    }

    #[\Override]
    public function with(?PathInterface $path = null, ?string $name = null): BeforePropertyEventInterface
    {
        return new self(
            name: $name ?? $this->name,
            path: $path ?? $this->path,
        );
    }
}
