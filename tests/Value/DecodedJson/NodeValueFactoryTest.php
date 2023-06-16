<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Value\DecodedJson;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Value\DecodedJson\Exception\InvalidNodeDataException;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeArrayValue;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeObjectValue;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeScalarValue;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactory;
use Remorhaz\JSON\Data\Path\Path;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;

use const STDOUT;

/**
 * @covers \Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactory
 */
class NodeValueFactoryTest extends TestCase
{
    /**
     * @dataProvider providerScalarDataOrNull
     */
    public function testCreateValue_ScalarDataOrNull_ReturnsNodeScalarValue(mixed $data): void
    {
        $actualValue = NodeValueFactory::create()->createValue($data, new Path());
        self::assertInstanceOf(NodeScalarValue::class, $actualValue);
    }

    /**
     * @return iterable<string, array{mixed, mixed}>
     */
    public static function providerScalarDataOrNull(): iterable
    {
        return [
            'Null' => [null, null],
            'Integer' => [1, 1],
            'Float' => [0.5, 0.5],
            'String' => ['a', 'a'],
            'Bool' => [true, true],
        ];
    }

    /**
     * @dataProvider providerScalarDataOrNull
     */
    public function testCreateValue_ScalarValueAndGivenPath_ResultHasSamePathInstance(mixed $data): void
    {
        $path = new Path();
        $actualValue = NodeValueFactory::create()->createValue($data, $path);
        self::assertSame($path, $actualValue->getPath());
    }

    /**
     * @dataProvider providerScalarDataOrNull
     */
    public function testCreateValue_ScalarValueAndNoPath_ResultHasEmptyPath(mixed $data): void
    {
        $actualValue = NodeValueFactory::create()->createValue($data);
        self::assertEmpty($actualValue->getPath()->getElements());
    }

    /**
     * @dataProvider providerScalarDataOrNull
     */
    public function testCreateValue_ScalarValue_ResultHasMatchingData(mixed $data, mixed $expectedValue): void
    {
        $path = new Path();
        /** @var ScalarValueInterface $actualValue */
        $actualValue = NodeValueFactory::create()->createValue($data, $path);
        self::assertSame($expectedValue, $actualValue->getData());
    }

    public function testCreateValue_ArrayValue_ReturnsNodeArrayValue(): void
    {
        $actualValue = NodeValueFactory::create()->createValue([], new Path());
        self::assertInstanceOf(NodeArrayValue::class, $actualValue);
    }

    public function testCreateValue_ArrayValueAndGivenPath_ResultHasSamePathInstance(): void
    {
        $path = new Path();
        $actualValue = NodeValueFactory::create()->createValue([], $path);
        self::assertSame($path, $actualValue->getPath());
    }

    public function testCreateValue_ArrayValueAndNoPath_ResultHasEmptyPath(): void
    {
        $actualValue = NodeValueFactory::create()->createValue([]);
        self::assertEmpty($actualValue->getPath()->getElements());
    }

    public function testCreateValue_ObjectValue_ReturnsNodeObjectValue(): void
    {
        $actualValue = NodeValueFactory::create()->createValue((object) [], new Path());
        self::assertInstanceOf(NodeObjectValue::class, $actualValue);
    }

    public function testCreateValue_ObjectValueAndGivenPath_ResultHasSamePathInstance(): void
    {
        $path = new Path();
        $actualValue = NodeValueFactory::create()->createValue((object) [], $path);
        self::assertSame($path, $actualValue->getPath());
    }

    public function testCreateValue_ObjectValueAndNoPath_ResultHasSamePathInstance(): void
    {
        $actualValue = NodeValueFactory::create()->createValue((object) []);
        self::assertEmpty($actualValue->getPath()->getElements());
    }

    public function testCreateValue_NonScalarValue_ThrowsException(): void
    {
        $factory = NodeValueFactory::create();
        $this->expectException(InvalidNodeDataException::class);
        $factory->createValue(STDOUT, new Path());
    }
}
