<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Walker;

use Iterator;
use Remorhaz\JSON\Data\Event\EventInterface;
use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;

final class ValueWalker implements ValueWalkerInterface
{
    /**
     * @return Iterator<EventInterface>
     */
    #[\Override]
    public function createEventIterator(NodeValueInterface $value, PathInterface $path): Iterator
    {
        return (new EventGenerator($value, $path))();
    }

    /**
     * @return Iterator<EventInterface>
     */
    #[\Override]
    public function createMutableEventIterator(
        NodeValueInterface $value,
        PathInterface $path,
        MutationInterface $modifier,
    ): Iterator {
        $modifier->reset();
        foreach ($this->createEventIterator($value, $path) as $event) {
            yield from $modifier($event, $this);
        }
    }
}
