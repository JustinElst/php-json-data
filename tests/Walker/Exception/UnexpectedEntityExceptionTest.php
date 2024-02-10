<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Walker\Exception;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Walker\Exception\UnexpectedEntityException;

#[CoversClass(UnexpectedEntityException::class)]
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
