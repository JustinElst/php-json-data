<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Walker\Exception;

use LogicException;
use Throwable;

final class UnexpectedEntityException extends LogicException implements ExceptionInterface
{
    public function __construct(
        private readonly mixed $entity,
        ?Throwable $previous = null,
    ) {
        parent::__construct("Invalid entity", previous: $previous);
    }

    public function getEntity(): mixed
    {
        return $this->entity;
    }
}
