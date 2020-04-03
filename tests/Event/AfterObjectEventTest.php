<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\AfterObjectEvent;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Event\AfterObjectEvent
 */
class AfterObjectEventTest extends TestCase
{

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new AfterObjectEvent($path);
        self::assertSame($path, $event->getPath());
    }
}
