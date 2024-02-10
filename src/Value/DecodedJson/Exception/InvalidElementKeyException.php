<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\DecodedJson\Exception;

use Remorhaz\JSON\Data\Exception\ExceptionInterface;
use Remorhaz\JSON\Data\Path\PathAwareInterface;
use Remorhaz\JSON\Data\Path\PathInterface;
use RuntimeException;
use Throwable;

use function gettype;
use function is_int;
use function is_string;

class InvalidElementKeyException extends RuntimeException implements ExceptionInterface, PathAwareInterface
{
    public function __construct(
        private readonly mixed $key,
        private readonly PathInterface $path,
        ?Throwable $previous = null,
    ) {
        parent::__construct($this->buildMessage(), previous: $previous);
    }

    private function buildMessage(): string
    {
        return "Invalid element key in decoded JSON: {$this->buildKey()} at {$this->buildPath()}";
    }

    public function getKey(): mixed
    {
        return $this->key;
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }

    private function buildKey(): string
    {
        if (is_string($this->key)) {
            return $this->key;
        }

        if (is_int($this->key)) {
            return (string) $this->key;
        }

        $type = gettype($this->key);

        return "<{$type}>";
    }

    private function buildPath(): string
    {
        return '/' . implode('/', $this->path->getElements());
    }
}
