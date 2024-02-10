<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

use function is_scalar;

final class ScalarEvent implements ScalarEventInterface
{
    private int|string|float|bool|null $data;

    public function __construct(
        mixed $data,
        private readonly PathInterface $path,
    ) {
        $this->data = null === $data || is_scalar($data)
            ? $data
            : throw new Exception\InvalidScalarDataException($data);
    }

    public function getData(): int|string|float|bool|null
    {
        return $this->data;
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }
}
