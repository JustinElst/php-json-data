<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Value\DecodedJson;

use PHPUnit\Framework\Constraint\Callback;
use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeObjectValue;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactoryInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactory;
use Remorhaz\JSON\Data\Path\Path;

use function iterator_to_array;

/**
 * @covers \Remorhaz\JSON\Data\Value\DecodedJson\NodeObjectValue
 */
class NodeObjectValueTest extends TestCase
{
    public function testCreateChildIterator_EmptyObjectData_ReturnsEmptyIterator(): void
    {
        $value = new NodeObjectValue((object) [], new Path(), NodeValueFactory::create());
        $actualData = iterator_to_array($value->createChildIterator(), true);
        self::assertSame([], $actualData);
    }

    public function testCreateChildIterator_NotEmptyObjectData_CallsFactoryForEachElement(): void
    {
        $nodeValueFactory = $this->createStub(NodeValueFactoryInterface::class);
        $value = new NodeObjectValue((object) ['a' => 'b', 'c' => 1], new Path('d'), $nodeValueFactory);

        $interceptedArgs = [];
        $nodeValueFactory
            ->method('createValue')
            ->willReturnCallback(
                function (mixed $data, ?PathInterface $path) use (&$interceptedArgs): NodeValueInterface {
                    /** @psalm-var array $interceptedArgs */
                    $interceptedArgs[] = [$data, $path?->getElements() ?? []];

                    return $this->createStub(NodeValueInterface::class);
                },
            );
        iterator_to_array($value->createChildIterator(), true);
        $expectedValue = [
            ['b', ['d', 'a']],
            [1, ['d', 'c']],
        ];
        self::assertSame($expectedValue, $interceptedArgs);
    }

    private static function isArgEqualPath(...$elements): Callback
    {
        return self::callback(
            fn (PathInterface $path): bool => $path->equals(new Path(...$elements)),
        );
    }

    public function testCreateChildIterator_NodeFactoryReturnsValues_ReturnsSameValuesWithMatchingIndexes(): void
    {
        $nodeValueFactory = $this->createMock(NodeValueFactoryInterface::class);
        $value = new NodeObjectValue((object) ['a' => 'b', 'c' => 1], new Path('d'), $nodeValueFactory);

        $firstNode = $this->createMock(NodeValueInterface::class);
        $secondNode = $this->createMock(NodeValueInterface::class);
        $nodeValueFactory
            ->method('createValue')
            ->willReturnOnConsecutiveCalls($firstNode, $secondNode);

        $actualValue = iterator_to_array($value->createChildIterator(), true);
        self::assertSame(['a' => $firstNode, 'c' => $secondNode], $actualValue);
    }

    public function testGetPath_ConstructedWithPath_ReturnsSameInstance(): void
    {
        $path = new Path();
        $value = new NodeObjectValue((object) [], $path, NodeValueFactory::create());
        self::assertSame($path, $value->getPath());
    }
}
