<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\BeforeArrayEvent;
use Remorhaz\JSON\Data\Path\Path;

#[CoversClass(BeforeArrayEvent::class)]
class BeforeArrayEventTest extends TestCase
{
    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new BeforeArrayEvent($path);
        self::assertSame($path, $event->getPath());
    }


    public function testWith_GivenNoPath_ResultHasOldPath(): void
    {
        $path = new Path();
        $event = new BeforeArrayEvent($path);
        $clone = $event->with();
        self::assertSame($path, $clone->getPath());
    }

    public function testWith_GivenNewPath_ResultHasNewPath(): void
    {
        $oldPath = new Path();
        $event = new BeforeArrayEvent($oldPath);
        $newPath = new Path();
        $clone = $event->with(path: $newPath);
        self::assertSame($newPath, $clone->getPath());
    }

    public function testWith_Called_ResultIsNewInstance(): void
    {
        $event = new BeforeArrayEvent(new Path());
        self::assertNotSame($event, $event->with());
    }
}
