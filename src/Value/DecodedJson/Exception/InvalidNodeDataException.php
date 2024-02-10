<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\DecodedJson\Exception;

use Remorhaz\JSON\Data\Value\DataAwareInterface;
use Remorhaz\JSON\Data\Exception\ExceptionInterface;
use Remorhaz\JSON\Data\Path\PathAwareInterface;
use Remorhaz\JSON\Data\Path\PathInterface;
use RuntimeException;
use Throwable;

class InvalidNodeDataException extends RuntimeException implements
    ExceptionInterface,
    PathAwareInterface,
    DataAwareInterface
{
    public function __construct(
        private readonly mixed $data,
        private readonly PathInterface $path,
        ?Throwable $previous = null,
    ) {
        parent::__construct($this->buildMessage(), previous: $previous);
    }

    private function buildMessage(): string
    {
        return "Invalid data in decoded JSON at {$this->buildPath()}";
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }

    private function buildPath(): string
    {
        return '/' . implode('/', $this->path->getElements());
    }
}
