<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

final class BeforePropertyEvent implements BeforePropertyEventInterface
{
    public function __construct(
        private string $name,
        private PathInterface $path,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }
}
