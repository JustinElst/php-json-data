<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Value\DecodedJson;

use PHPUnit\Framework\Constraint\Callback;
use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactoryInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use function iterator_to_array;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeArrayValue;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactory;
use Remorhaz\JSON\Data\Value\DecodedJson\Exception\InvalidElementKeyException;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Value\DecodedJson\NodeArrayValue
 */
class NodeArrayValueTest extends TestCase
{

    /**
     * @param array $data
     * @dataProvider providerArrayWithInvalidIndex
     */
    public function testCreateChildIterator_ArrayDataWithInvalidIndex_ThrowsException(array $data): void
    {
        $value = new NodeArrayValue($data, new Path, NodeValueFactory::create());

        $this->expectException(InvalidElementKeyException::class);
        iterator_to_array($value->createChildIterator(), true);
    }

    public function providerArrayWithInvalidIndex(): array
    {
        return [
            'Non-zero first index' => [[1 => 'a']],
            'Non-integer first index' => [['a' => 'b']],
        ];
    }

    public function testCreateChildIterator_EmptyArrayData_ReturnsEmptyIterator(): void
    {
        $value = new NodeArrayValue([], new Path, NodeValueFactory::create());
        $actualData = iterator_to_array($value->createChildIterator(), true);
        self::assertSame([], $actualData);
    }

    public function testCreateChildIterator_NotEmptyArrayData_CallsFactoryForEachElement(): void
    {
        $nodeValueFactory = $this->createMock(NodeValueFactoryInterface::class);
        $value = new NodeArrayValue(['a', 1], new Path('b'), $nodeValueFactory);

        $nodeValueFactory
            ->expects(self::exactly(2))
            ->method('createValue')
            ->withConsecutive(
                [self::identicalTo('a'), $this->isArgEqualPath('b', 0)],
                [self::identicalTo(1), $this->isArgEqualPath('b', 1)],
            );
        iterator_to_array($value->createChildIterator(), true);
    }

    private function isArgEqualPath(...$elements): Callback
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
        $path = new Path;
        $value = new NodeArrayValue([], $path, NodeValueFactory::create());
        self::assertSame($path, $value->getPath());
    }
}
