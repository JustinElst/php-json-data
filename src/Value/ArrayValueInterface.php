<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value;

use Iterator;

interface ArrayValueInterface extends StructValueInterface
{
    /**
     * @return Iterator<int, NodeValueInterface>
     */
    public function createChildIterator(): Iterator;
}
