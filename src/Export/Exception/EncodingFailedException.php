<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Export\Exception;

use LogicException;
use Remorhaz\JSON\Data\Value\DataAwareInterface;
use Throwable;

final class EncodingFailedException extends LogicException implements ExceptionInterface, DataAwareInterface
{
    public function __construct(
        private readonly mixed $data,
        ?Throwable $previous = null,
    ) {
        parent::__construct("Failed to encode data to JSON", previous: $previous);
    }

    #[\Override]
    public function getData(): mixed
    {
        return $this->data;
    }
}
