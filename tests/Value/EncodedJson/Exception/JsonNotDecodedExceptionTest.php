<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Value\EncodedJson\Exception;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Value\EncodedJson\Exception\JsonNotDecodedException;

#[CoversClass(JsonNotDecodedException::class)]
class JsonNotDecodedExceptionTest extends TestCase
{
    public function testGetMessage_Constructed_ReturnsMatchingValue(): void
    {
        $exception = new JsonNotDecodedException('a');
        self::assertSame('Failed to decode JSON', $exception->getMessage());
    }

    public function testGetJson_ConstructedWithJson_ReturnsSameValue(): void
    {
        $exception = new JsonNotDecodedException('a');
        self::assertSame('a', $exception->getJson());
    }

    public function testGetPrevious_ConstructedWithoutPrevious_ReturnsNull(): void
    {
        $exception = new JsonNotDecodedException('a');
        self::assertNull($exception->getPrevious());
    }

    public function testGetPrevious_ConstructedWithPrevious_ReturnsSameInstance(): void
    {
        $previous = new Exception();
        $exception = new JsonNotDecodedException('a', $previous);
        self::assertSame($previous, $exception->getPrevious());
    }
}
