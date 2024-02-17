<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

interface AfterPropertyEventInterface extends PropertyEventInterface
{
    public function with(?PathInterface $path = null, ?string $name = null): AfterPropertyEventInterface;
}
