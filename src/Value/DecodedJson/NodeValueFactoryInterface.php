<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\DecodedJson;

use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Data\Path\PathInterface;

interface NodeValueFactoryInterface
{
    /**
     * Converts decoded JSON to JSON node value.
     */
    public function createValue(mixed $data, ?PathInterface $path = null): NodeValueInterface;
}
