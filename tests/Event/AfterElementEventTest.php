<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\AfterElementEvent;
use Remorhaz\JSON\Data\Path\Path;

#[CoversClass(AfterElementEvent::class)]
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

    public function testWith_GivenNoPath_ResultHasOldPath(): void
    {
        $path = new Path();
        $event = new AfterElementEvent(1, $path);
        $clone = $event->with();
        self::assertSame($path, $clone->getPath());
    }

    public function testWith_GivenNewPath_ResultHasNewPath(): void
    {
        $oldPath = new Path();
        $event = new AfterElementEvent(1, $oldPath);
        $newPath = new Path();
        $clone = $event->with(path: $newPath);
        self::assertSame($newPath, $clone->getPath());
    }

    public function testWith_Called_ResultIsNewInstance(): void
    {
        $event = new AfterElementEvent(1, new Path());
        self::assertNotSame($event, $event->with());
    }

    public function testWith_GivenNoIndex_ResultHasOldIndex(): void
    {
        $event = new AfterElementEvent(1, new Path());
        $clone = $event->with();
        self::assertSame(1, $clone->getIndex());
    }

    public function testWith_GivenNewIndex_ResultHasNewIndex(): void
    {
        $event = new AfterElementEvent(1, new Path());
        $clone = $event->with(index: 2);
        self::assertSame(2, $clone->getIndex());
    }
}
