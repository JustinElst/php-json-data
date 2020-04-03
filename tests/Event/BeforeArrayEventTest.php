<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\BeforeArrayEvent;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Event\BeforeArrayEvent
 */
class BeforeArrayEventTest extends TestCase
{

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new BeforeArrayEvent($path);
        self::assertSame($path, $event->getPath());
    }
}
