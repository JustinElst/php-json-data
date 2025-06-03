<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Export\Exception;

use DomainException;
use Remorhaz\JSON\Data\Value\ValueInterface;
use Throwable;

final class UnexpectedValueException extends DomainException implements ExceptionInterface
{
    public function __construct(
        private readonly ValueInterface $value,
        ?Throwable $previous = null,
    ) {
        parent::__construct("Unexpected value", previous: $previous);
    }

    public function getValue(): ValueInterface
    {
        return $this->value;
    }
}
