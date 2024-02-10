<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\DecodedJson;

use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;

use function is_scalar;

final class NodeScalarValue implements NodeValueInterface, ScalarValueInterface
{
    private int|float|string|bool|null $data;

    public function __construct(
        mixed $data,
        private readonly PathInterface $path,
    ) {
        $this->data = null === $data || is_scalar($data)
            ? $data
            : throw new Exception\InvalidNodeDataException($data, $path);
    }

    public function getData(): int|float|string|bool|null
    {
        return $this->data;
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }
}
