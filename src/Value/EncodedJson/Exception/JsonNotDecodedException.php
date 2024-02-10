<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\EncodedJson\Exception;

use RuntimeException;
use Throwable;

final class JsonNotDecodedException extends RuntimeException implements ExceptionInterface
{
    public function __construct(
        private readonly string $json,
        ?Throwable $previous = null,
    ) {
        parent::__construct("Failed to decode JSON", previous: $previous);
    }

    public function getJson(): string
    {
        return $this->json;
    }
}
