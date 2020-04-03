<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\AfterArrayEvent;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Event\AfterArrayEvent
 */
class AfterArrayEventTest extends TestCase
{

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new AfterArrayEvent($path);
        self::assertSame($path, $event->getPath());
    }
}
