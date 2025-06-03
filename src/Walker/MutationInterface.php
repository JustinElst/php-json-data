<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Walker;

use Iterator;
use Remorhaz\JSON\Data\Event\EventInterface;

interface MutationInterface
{
    /**
     * @return Iterator<EventInterface>
     */
    public function __invoke(EventInterface $event, ValueWalkerInterface $valueWalker): Iterator;

    public function reset(): void;
}
