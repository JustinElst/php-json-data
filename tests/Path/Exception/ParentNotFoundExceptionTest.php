<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Path\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Path\Exception\ParentNotFoundException;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Path\Exception\ParentNotFoundException
 */
class ParentNotFoundExceptionTest extends TestCase
{
    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $exception = new ParentNotFoundException($path);
        self::assertSame($path, $exception->getPath());
    }

    /**
     * @param list<mixed> $pathElements
     * @param string      $expectedValue
     * @dataProvider providerGetMessage
     */
    public function testGetMessage(array $pathElements, string $expectedValue): void
    {
        $path = new Path(...$pathElements);
        $exception = new ParentNotFoundException($path);
        self::assertSame($expectedValue, $exception->getMessage());
    }

    /**
     * @return iterable<string, array{list<mixed>, string}>
     */
    public static function providerGetMessage(): iterable
    {
        return [
            'Empty path' => [[], "Parent not found in path /"],
            'Non-empty path' => [['a', 1], "Parent not found in path /a/1"],
        ];
    }

    public function testGetCode_Always_ReturnsZero(): void
    {
        $exception = new ParentNotFoundException(new Path());
        self::assertSame(0, $exception->getCode());
    }

    public function testGetPrevious_ConstructedWithoutPrevious_ReturnsNull(): void
    {
        $exception = new ParentNotFoundException(new Path());
        self::assertNull($exception->getPrevious());
    }

    public function testGetPrevious_ConstructedWithPrevious_ReturnsSameInstance(): void
    {
        $previous = new Exception();
        $exception = new ParentNotFoundException(new Path(), $previous);
        self::assertSame($previous, $exception->getPrevious());
    }
}
