<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\BeforePropertyEvent;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Event\BeforePropertyEvent
 */
class BeforePropertyEventTest extends TestCase
{

    public function testGetName_ConstructedWithName_ReturnsSameIndex(): void
    {
        $event = new BeforePropertyEvent('a', new Path);
        self::assertSame('a', $event->getName());
    }

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path;
        $event = new BeforePropertyEvent('a', $path);
        self::assertSame($path, $event->getPath());
    }
}
