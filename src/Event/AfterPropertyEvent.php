<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

final readonly class AfterPropertyEvent implements AfterPropertyEventInterface
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
    public function with(?PathInterface $path = null, ?string $name = null): AfterPropertyEventInterface
    {
        return new self(
            name: $name ?? $this->name,
            path: $path ?? $this->path,
        );
    }
}
