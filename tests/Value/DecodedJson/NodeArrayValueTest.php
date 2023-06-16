<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Value\DecodedJson;

use PHPUnit\Framework\Constraint\Callback;
use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactoryInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeArrayValue;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactory;
use Remorhaz\JSON\Data\Value\DecodedJson\Exception\InvalidElementKeyException;
use Remorhaz\JSON\Data\Path\Path;

use function iterator_to_array;

/**
 * @covers \Remorhaz\JSON\Data\Value\DecodedJson\NodeArrayValue
 */
class NodeArrayValueTest extends TestCase
{
    /**
     * @dataProvider providerArrayWithInvalidIndex
     */
    public function testCreateChildIterator_ArrayDataWithInvalidIndex_ThrowsException(array $data): void
    {
        $value = new NodeArrayValue($data, new Path(), NodeValueFactory::create());

        $this->expectException(InvalidElementKeyException::class);
        iterator_to_array($value->createChildIterator(), true);
    }

    /**
     * @return iterable<string, array{array}>
     */
    public static function providerArrayWithInvalidIndex(): iterable
    {
        return [
            'Non-zero first index' => [[1 => 'a']],
            'Non-integer first index' => [['a' => 'b']],
        ];
    }

    public function testCreateChildIterator_EmptyArrayData_ReturnsEmptyIterator(): void
    {
        $value = new NodeArrayValue([], new Path(), NodeValueFactory::create());
        $actualData = iterator_to_array($value->createChildIterator(), true);
        self::assertSame([], $actualData);
    }

    public function testCreateChildIterator_NotEmptyArrayData_CallsFactoryForEachElement(): void
    {
        $nodeValueFactory = $this->createMock(NodeValueFactoryInterface::class);
        $value = new NodeArrayValue(['a', 1], new Path('b'), $nodeValueFactory);

        $interceptedArguments = [];
        $nodeValueFactory
            ->method('createValue')
            ->willReturnCallback(
                function (mixed $data, ?PathInterface $path) use (&$interceptedArguments): NodeValueInterface {
                    /** @psalm-var array $interceptedArguments */
                    $interceptedArguments[] = [$data, $path?->getElements() ?? []];

                    return $this->createStub(NodeValueInterface::class);
                }
            );
        iterator_to_array($value->createChildIterator(), true);
        $expectedValue = [
            ['a', ['b', 0]],
            [1, ['b', 1]],
        ];
        self::assertSame($expectedValue, $interceptedArguments);
    }

    private function isArgEqualPath(int|string ...$elements): Callback
    {
        $callback = function (PathInterface $path) use ($elements): bool {
            return $path->equals(new Path(...$elements));
        };

        return self::callback($callback);
    }

    public function testCreateChildIterator_NodeFactoryReturnsValues_ReturnsSameValuesWithMatchingIndexes(): void
    {
        $nodeValueFactory = $this->createMock(NodeValueFactoryInterface::class);
        $value = new NodeArrayValue(['a', 1], new Path('b'), $nodeValueFactory);

        $firstNode = $this->createMock(NodeValueInterface::class);
        $secondNode = $this->createMock(NodeValueInterface::class);
        $nodeValueFactory
            ->method('createValue')
            ->willReturnOnConsecutiveCalls($firstNode, $secondNode);

        $actualValue = iterator_to_array($value->createChildIterator(), true);
        self::assertSame([0 => $firstNode, 1 => $secondNode], $actualValue);
    }

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $value = new NodeArrayValue([], $path, NodeValueFactory::create());
        self::assertSame($path, $value->getPath());
    }
}
