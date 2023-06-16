<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Value\DecodedJson;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeScalarValue;
use Remorhaz\JSON\Data\Value\DecodedJson\Exception\InvalidNodeDataException;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Value\DecodedJson\NodeScalarValue
 */
class NodeScalarValueTest extends TestCase
{
    /**
     * @dataProvider providerInvalidData
     */
    public function testConstruct_InvalidData_ThrowsMatchingException(mixed $data): void
    {
        $this->expectException(InvalidNodeDataException::class);
        new NodeScalarValue($data, new Path());
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function providerInvalidData(): iterable
    {
        return [
            'Resource' => [STDERR],
            'Invalid object' => [
                new class
                {
                },
            ],
            'Array' => [[]],
            'Object' => [(object) []],
        ];
    }

    public function testGetData_ConstructedWithValidData_ReturnsSameValue(): void
    {
        $nodeValue = new NodeScalarValue('a', new Path());
        self::assertSame('a', $nodeValue->getData());
    }

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $nodeValue = new NodeScalarValue('a', $path);
        self::assertSame($path, $nodeValue->getPath());
    }
}
