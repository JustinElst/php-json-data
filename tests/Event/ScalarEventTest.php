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

    public function testWith_GivenNoPath_ResultHasOldPath(): void
    {
        $path = new Path();
        $event = new ScalarEvent(null, $path);
        $clone = $event->with();
        self::assertSame($path, $clone->getPath());
    }

    public function testWith_GivenNewPath_ResultHasNewPath(): void
    {
        $oldPath = new Path();
        $event = new ScalarEvent(null, $oldPath);
        $newPath = new Path();
        $clone = $event->with(path: $newPath);
        self::assertSame($newPath, $clone->getPath());
    }

    public function testWith_Called_ResultIsNewInstance(): void
    {
        $event = new ScalarEvent(null, new Path());
        self::assertNotSame($event, $event->with());
    }

    public function testWith_GivenNoData_ResultHasOldData(): void
    {
        $event = new ScalarEvent('a', new Path());
        $clone = $event->with();
        self::assertSame('a', $clone->getData());
    }

    public function testWith_GivenNewNonNullData_ResultHasNewData(): void
    {
        $event = new ScalarEvent('a', new Path());
        $clone = $event->with(data: 'b');
        self::assertSame('b', $clone->getData());
    }

    public function testWith_GivenNewNullDataWithoutForceFlag_ResultHasOldData(): void
    {
        $event = new ScalarEvent('a', new Path());
        $clone = $event->with(data: null);
        self::assertSame('a', $clone->getData());
    }

    public function testWith_GivenNewNullDataWithForceFlag_ResultHasNewData(): void
    {
        $event = new ScalarEvent('a', new Path());
        $clone = $event->with(data: null, forceData: true);
        self::assertSame(null, $clone->getData());
    }
}
