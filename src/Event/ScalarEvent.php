<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\Path\PathInterface;

use function is_scalar;

final readonly class ScalarEvent implements ScalarEventInterface
{
    private int|string|float|bool|null $data;

    public function __construct(
        mixed $data,
        private PathInterface $path,
    ) {
        $this->data = null === $data || is_scalar($data)
            ? $data
            : throw new Exception\InvalidScalarDataException($data);
    }

    #[\Override]
    public function getData(): int|string|float|bool|null
    {
        return $this->data;
    }

    #[\Override]
    public function getPath(): PathInterface
    {
        return $this->path;
    }

    #[\Override]
    public function with(
        ?PathInterface $path = null,
        float|bool|int|string|null $data = null,
        bool $forceData = false,
    ): ScalarEventInterface {
        return new self(
            data: $forceData ? $data : ($data ?? $this->data),
            path: $path ?? $this->path,
        );
    }
}
