<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\BeforePropertyEvent;
use Remorhaz\JSON\Data\Path\Path;

#[CoversClass(BeforePropertyEvent::class)]
class BeforePropertyEventTest extends TestCase
{
    public function testGetName_ConstructedWithName_ReturnsSameIndex(): void
    {
        $event = new BeforePropertyEvent('a', new Path());
        self::assertSame('a', $event->getName());
    }

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new BeforePropertyEvent('a', $path);
        self::assertSame($path, $event->getPath());
    }

    public function testWith_GivenNoPath_ResultHasOldPath(): void
    {
        $path = new Path();
        $event = new BeforePropertyEvent('a', $path);
        $clone = $event->with();
        self::assertSame($path, $clone->getPath());
    }

    public function testWith_GivenNewPath_ResultHasNewPath(): void
    {
        $oldPath = new Path();
        $event = new BeforePropertyEvent('a', $oldPath);
        $newPath = new Path();
        $clone = $event->with(path: $newPath);
        self::assertSame($newPath, $clone->getPath());
    }

    public function testWith_Called_ResultIsNewInstance(): void
    {
        $event = new BeforePropertyEvent('a', new Path());
        self::assertNotSame($event, $event->with());
    }

    public function testWith_GivenNoName_ResultHasOldName(): void
    {
        $event = new BeforePropertyEvent('a', new Path());
        $clone = $event->with();
        self::assertSame('a', $clone->getName());
    }

    public function testWith_GivenNewName_ResultHasNewName(): void
    {
        $event = new BeforePropertyEvent('a', new Path());
        $clone = $event->with(name: 'b');
        self::assertSame('b', $clone->getName());
    }
}
