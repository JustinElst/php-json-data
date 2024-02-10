<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\Exception\InvalidScalarDataException;
use Remorhaz\JSON\Data\Event\ScalarEvent;
use Remorhaz\JSON\Data\Path\Path;

#[CoversClass(ScalarEvent::class)]
class ScalarEventTest extends TestCase
{
    public function testConstruct_NonScalarData_ThrowsException(): void
    {
        $this->expectException(InvalidScalarDataException::class);
        new ScalarEvent([], new Path());
    }

    #[DataProvider('providerGetData')]
    public function testGetData_ConstructedWithScalarData_ReturnsSameValue(mixed $data, mixed $expectedValue): void
    {
        $event = new ScalarEvent($data, new Path());
        self::assertSame($expectedValue, $event->getData());
    }

    /**
     * @return iterable<string, array{mixed, mixed}>
     */
    public static function providerGetData(): iterable
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
