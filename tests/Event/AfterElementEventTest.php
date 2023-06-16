<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\AfterElementEvent;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Event\AfterElementEvent
 */
class AfterElementEventTest extends TestCase
{
    public function testGetIndex_ConstructedWithIndex_ReturnsSameIndex(): void
    {
        $event = new AfterElementEvent(1, new Path());
        self::assertSame(1, $event->getIndex());
    }

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new AfterElementEvent(1, $path);
        self::assertSame($path, $event->getPath());
    }
}
