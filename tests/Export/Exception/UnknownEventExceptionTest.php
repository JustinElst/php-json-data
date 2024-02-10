<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Export\Exception;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\EventInterface;
use Remorhaz\JSON\Data\Export\Exception\UnknownEventException;

#[CoversClass(UnknownEventException::class)]
class UnknownEventExceptionTest extends TestCase
{
    public function testGetMessage_Constructed_ReturnsMatchingValue(): void
    {
        $exception = new UnknownEventException(self::createStub(EventInterface::class));
        self::assertSame('Unknown event', $exception->getMessage());
    }

    public function testGetEvent_ConstructedWithData_ReturnsSameValue(): void
    {
        $event = self::createStub(EventInterface::class);
        $exception = new UnknownEventException($event);
        self::assertSame($event, $exception->getEvent());
    }

    public function testGetPrevious_ConstructedWithoutPrevious_ReturnsNull(): void
    {
        $exception = new UnknownEventException(self::createStub(EventInterface::class));
        self::assertNull($exception->getPrevious());
    }

    public function testGetPrevious_ConstructedWithPrevious_ReturnsSameInstance(): void
    {
        $previous = new Exception();
        $exception = new UnknownEventException(
            self::createStub(EventInterface::class),
            $previous,
        );
        self::assertSame($previous, $exception->getPrevious());
    }
}
