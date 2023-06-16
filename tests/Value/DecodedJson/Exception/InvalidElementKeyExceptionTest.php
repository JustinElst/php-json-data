<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Value\DecodedJson;

use Exception;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Value\DecodedJson\Exception\InvalidElementKeyException;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Value\DecodedJson\Exception\InvalidElementKeyException
 */
class InvalidElementKeyExceptionTest extends TestCase
{
    /**
     * @param mixed $key
     * @param list<int|string> $pathElements
     * @param string $expectedValue
     * @dataProvider providerKeyMessage
     */
    public function testGetMessage_Constructed_ReturnsMatchingValue(
        mixed $key,
        array $pathElements,
        string $expectedValue,
    ): void {
        $exception = new InvalidElementKeyException($key, new Path(...$pathElements));
        self::assertSame($expectedValue, $exception->getMessage());
    }

    /**
     * @return iterable<string, array{mixed, list<int|string>, string}>
     */
    public static function providerKeyMessage(): iterable
    {
        /** @noinspection HtmlUnknownTag */
        return [
            'Float key with empty path' => [0.5, [], 'Invalid element key in decoded JSON: <double> at /'],
            'Integer key with empty path' => [1, [], 'Invalid element key in decoded JSON: 1 at /'],
            'String key with non-empty path' => ['a', ['b', 1], 'Invalid element key in decoded JSON: a at /b/1'],
        ];
    }

    public function testGetPath_ConstructedWithGivenPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $exception = new InvalidElementKeyException(0, $path);
        self::assertSame($path, $exception->getPath());
    }

    /**
     * @dataProvider providerKey
     */
    public function testGetKey_ConstructedWithGivenKey_ReturnsSameValue(mixed $key, mixed $expectedValue): void
    {
        $exception = new InvalidElementKeyException($key, new Path());
        self::assertSame($expectedValue, $exception->getKey());
    }

    /**
     * @return iterable<string, array{mixed, mixed}>
     */
    public static function providerKey(): iterable
    {
        return [
            'Integer' => [1, 1],
            'String' => ['a', 'a'],
        ];
    }

    public function testGetCode_Always_ReturnZero(): void
    {
        $exception = new InvalidElementKeyException(0, new Path());
        self::assertSame(0, $exception->getCode());
    }

    public function testGetPrevious_ConstructedWithoutPrevious_ReturnsNull(): void
    {
        $exception = new InvalidElementKeyException(0, new Path());
        self::assertNull($exception->getPrevious());
    }

    public function testGetPrevious_ConstructedWithGivenPrevious_ReturnsSameInstance(): void
    {
        $previous = new Exception();
        $exception = new InvalidElementKeyException(0, new Path(), $previous);
        self::assertSame($previous, $exception->getPrevious());
    }
}
