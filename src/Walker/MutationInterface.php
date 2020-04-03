<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Walker;

use Remorhaz\JSON\Data\Event\EventInterface;

interface MutationInterface
{

    public function __invoke(EventInterface $event, ValueWalkerInterface $valueWalker);

    public function reset(): void;
}
