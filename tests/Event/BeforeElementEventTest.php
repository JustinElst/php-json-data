<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\BeforeElementEvent;
use Remorhaz\JSON\Data\Path\Path;

#[CoversClass(BeforeElementEvent::class)]
class BeforeElementEventTest extends TestCase
{
    public function testGetIndex_ConstructedWithIndex_ReturnsSameIndex(): void
    {
        $event = new BeforeElementEvent(1, new Path());
        self::assertSame(1, $event->getIndex());
    }

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new BeforeElementEvent(1, $path);
        self::assertSame($path, $event->getPath());
    }
}
