<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\Exception\InvalidScalarDataException;
use Remorhaz\JSON\Data\Event\ScalarEvent;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Event\ScalarEvent
 */
class ScalarEventTest extends TestCase
{

    public function testConstruct_NonScalarData_ThrowsException(): void
    {
        $this->expectException(InvalidScalarDataException::class);
        new ScalarEvent([], new Path());
    }

    /**
     * @param $data
     * @param $expectedValue
     * @dataProvider providerGetData
     */
    public function testGetData_ConstructedWithScalarData_ReturnsSameValue($data, $expectedValue): void
    {
        $event = new ScalarEvent($data, new Path());
        self::assertSame($expectedValue, $event->getData());
    }

    public function providerGetData(): array
    {
        return [
            'Null' => [null, null],
            'String' => ['a', 'a'],
            'Integer' => [1, 1],
            'Float' => [1.2, 1.2],
            'Boolean' => [true, true],
        ];
    }

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $event = new ScalarEvent(null, $path);
        self::assertSame($path, $event->getPath());
    }
}
