<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Export\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Export\Exception\UnexpectedValueException;
use Remorhaz\JSON\Data\Value\ValueInterface;

/**
 * @covers \Remorhaz\JSON\Data\Export\Exception\UnexpectedValueException
 */
class UnexpectedValueExceptionTest extends TestCase
{

    public function testGetMessage_Constructed_ReturnsMatchingValue(): void
    {
        $exception = new UnexpectedValueException($this->createMock(ValueInterface::class));
        self::assertSame('Unexpected value', $exception->getMessage());
    }

    public function testGetValue_ConstructedWithData_ReturnsSameValue(): void
    {
        $value = $this->createMock(ValueInterface::class);
        $exception = new UnexpectedValueException($value);
        self::assertSame($value, $exception->getValue());
    }

    public function testGetCode_Always_ReturnsZero(): void
    {
        $exception = new UnexpectedValueException($this->createMock(ValueInterface::class));
        self::assertSame(0, $exception->getCode());
    }

    public function testGetPrevious_ConstructedWithoutPrevious_ReturnsNull(): void
    {
        $exception = new UnexpectedValueException($this->createMock(ValueInterface::class));
        self::assertNull($exception->getPrevious());
    }

    public function testGetPrevious_ConstructedWithPrevious_ReturnsSameInstance(): void
    {
        $previous = new Exception();
        $exception = new UnexpectedValueException(
            $this->createMock(ValueInterface::class),
            $previous,
        );
        self::assertSame($previous, $exception->getPrevious());
    }
}
