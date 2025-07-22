<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value;

use Iterator;

interface ObjectValueInterface extends StructValueInterface
{
    /**
     * @return Iterator<string, NodeValueInterface>
     */
    #[\Override]
    public function createChildIterator(): Iterator;
}
