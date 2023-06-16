<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Event\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\Exception\InvalidScalarDataException;

/**
 * @covers \Remorhaz\JSON\Data\Event\Exception\InvalidScalarDataException
 */
class InvalidScalarDataExceptionTest extends TestCase
{
    public function testGetMessage_Constructed_ReturnsMatchingValue(): void
    {
        $exception = new InvalidScalarDataException(null);
        self::assertSame('Invalid scalar data', $exception->getMessage());
    }

    public function testGetData_ConstructedWithData_ReturnsSameValue(): void
    {
        $exception = new InvalidScalarDataException('a');
        self::assertSame('a', $exception->getData());
    }

    public function testGetCode_Always_ReturnsZero(): void
    {
        $exception = new InvalidScalarDataException(null);
        self::assertSame(0, $exception->getCode());
    }

    public function testGetPrevious_ConstructedWithoutPrevious_ReturnsNull(): void
    {
        $exception = new InvalidScalarDataException(null);
        self::assertNull($exception->getPrevious());
    }

    public function testGetPrevious_ConstructedWithPrevious_ReturnsSameInstance(): void
    {
        $previous = new Exception();
        $exception = new InvalidScalarDataException(null, $previous);
        self::assertSame($previous, $exception->getPrevious());
    }
}
