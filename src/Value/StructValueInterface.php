<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value;

use Iterator;

interface StructValueInterface extends ValueInterface
{
    /**
     * @return Iterator<int|string, NodeValueInterface>
     */
    public function createChildIterator(): Iterator;
}
