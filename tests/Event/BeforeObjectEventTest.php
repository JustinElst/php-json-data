<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\BeforeObjectEvent;
use Remorhaz\JSON\Data\Path\Path;

#[CoversClass(BeforeObjectEvent::class)]
class BeforeObjectEventTest extends TestCase
{
    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new BeforeObjectEvent($path);
        self::assertSame($path, $event->getPath());
    }

    public function testWith_GivenNoPath_ResultHasOldPath(): void
    {
        $path = new Path();
        $event = new BeforeObjectEvent($path);
        $clone = $event->with();
        self::assertSame($path, $clone->getPath());
    }

    public function testWith_GivenNewPath_ResultHasNewPath(): void
    {
        $oldPath = new Path();
        $event = new BeforeObjectEvent($oldPath);
        $newPath = new Path();
        $clone = $event->with(path: $newPath);
        self::assertSame($newPath, $clone->getPath());
    }

    public function testWith_Called_ResultIsNewInstance(): void
    {
        $event = new BeforeObjectEvent(new Path());
        self::assertNotSame($event, $event->with());
    }
}
