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
}
