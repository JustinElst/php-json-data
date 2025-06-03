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

final class InvalidElementKeyException extends RuntimeException implements ExceptionInterface, PathAwareInterface
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
        return sprintf('Invalid element key in decoded JSON: %s at %s', $this->buildKey(), $this->buildPath());
    }

    public function getKey(): mixed
    {
        return $this->key;
    }

    #[\Override]
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

        return sprintf('<%s>', $type);
    }

    private function buildPath(): string
    {
        return '/' . implode('/', $this->path->getElements());
    }
}
