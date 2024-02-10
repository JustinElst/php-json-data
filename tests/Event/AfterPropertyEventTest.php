<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\AfterPropertyEvent;
use Remorhaz\JSON\Data\Path\Path;

#[CoversClass(AfterPropertyEvent::class)]
class AfterPropertyEventTest extends TestCase
{
    public function testGetName_ConstructedWithName_ReturnsSameIndex(): void
    {
        $event = new AfterPropertyEvent('a', new Path());
        self::assertSame('a', $event->getName());
    }

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new AfterPropertyEvent('a', $path);
        self::assertSame($path, $event->getPath());
    }
}
