<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Export;

use Iterator;
use Remorhaz\JSON\Data\Event\EventInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;

interface EventExporterInterface
{
    /**
     * @param Iterator<EventInterface> $events
     */
    public function exportEvents(Iterator $events): ?NodeValueInterface;

    /**
     * @param Iterator<EventInterface> $events
     */
    public function exportExistingEvents(Iterator $events): NodeValueInterface;
}
