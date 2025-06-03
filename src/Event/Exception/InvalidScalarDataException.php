<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event\Exception;

use LogicException;
use Remorhaz\JSON\Data\Value\DataAwareInterface;
use Throwable;

final class InvalidScalarDataException extends LogicException implements ExceptionInterface, DataAwareInterface
{
    public function __construct(
        private readonly mixed $data,
        ?Throwable $previous = null,
    ) {
        parent::__construct("Invalid scalar data", previous: $previous);
    }

    #[\Override]
    public function getData(): mixed
    {
        return $this->data;
    }
}
