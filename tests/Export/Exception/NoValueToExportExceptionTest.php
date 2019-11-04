<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Export\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Export\Exception\NoValueToExportException;

/**
 * @covers \Remorhaz\JSON\Data\Export\Exception\NoValueToExportException
 */
class NoValueToExportExceptionTest extends TestCase
{

    public function testGetMessage_Constructed_ReturnsMatchingValue(): void
    {
        $exception = new NoValueToExportException;
        self::assertSame('No value to export', $exception->getMessage());
    }

    public function testGetCode_Always_ReturnsZero(): void
    {
        $exception = new NoValueToExportException;
        self::assertSame(0, $exception->getCode());
    }

    public function testGetPrevious_ConstructedWithoutPrevious_ReturnsNull(): void
    {
        $exception = new NoValueToExportException;
        self::assertNull($exception->getPrevious());
    }

    public function testGetPrevious_ConstructedWithPrevious_ReturnsSameInstance(): void
    {
        $previous = new Exception;
        $exception = new NoValueToExportException($previous);
        self::assertSame($previous, $exception->getPrevious());
    }
}
