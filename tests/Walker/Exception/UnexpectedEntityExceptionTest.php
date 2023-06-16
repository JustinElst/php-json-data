<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Walker\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Walker\Exception\UnexpectedEntityException;

/**
 * @covers \Remorhaz\JSON\Data\Walker\Exception\UnexpectedEntityException
 */
class UnexpectedEntityExceptionTest extends TestCase
{
    public function testGetMessage_Constructed_ReturnsMatchingValue(): void
    {
        $exception = new UnexpectedEntityException('a');
        self::assertSame('Invalid entity', $exception->getMessage());
    }

    public function testGetEntity_ConstructedWithEntity_ReturnsSameValue(): void
    {
        $exception = new UnexpectedEntityException('a');
        self::assertSame('a', $exception->getEntity());
    }

    public function testGetCode_Always_ReturnsZero(): void
    {
        $exception = new UnexpectedEntityException('a');
        self::assertSame(0, $exception->getCode());
    }

    public function testGetPrevious_ConstructedWithoutPrevious_ReturnsNull(): void
    {
        $exception = new UnexpectedEntityException('a');
        self::assertNull($exception->getPrevious());
    }

    public function testGetPrevious_ConstructedWithPrevious_ReturnsSameInstance(): void
    {
        $previous = new Exception();
        $exception = new UnexpectedEntityException('a', $previous);
        self::assertSame($previous, $exception->getPrevious());
    }
}
