<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Walker;

use Iterator;
use Remorhaz\JSON\Data\Event\EventInterface;

interface MutationInterface
{
    /**
     * @param EventInterface       $event
     * @param ValueWalkerInterface $valueWalker
     * @return Iterator<EventInterface>
     */
    public function __invoke(EventInterface $event, ValueWalkerInterface $valueWalker): Iterator;

    public function reset(): void;
}
