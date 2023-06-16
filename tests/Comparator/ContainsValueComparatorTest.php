<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Comparator;

use Collator;
use Generator;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Comparator\ContainsValueComparator;
use Remorhaz\JSON\Data\Value\EncodedJson\NodeValueFactory;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;
use Remorhaz\JSON\Data\Value\ValueInterface;

/**
 * @covers \Remorhaz\JSON\Data\Comparator\ContainsValueComparator
 */
class ContainsValueComparatorTest extends TestCase
{
    /**
     * @param string $data
     * @param string $containedData
     * @dataProvider providerMatchingValues
     */
    public function testCompare_MatchingValues_ReturnsTrue(string $data, string $containedData): void
    {
        $comparator = new ContainsValueComparator(new Collator('UTF-8'));
        $nodeValueFactory = NodeValueFactory::create();
        $actualValue = $comparator->compare(
            $nodeValueFactory->createValue($data),
            $nodeValueFactory->createValue($containedData)
        );
        self::assertTrue($actualValue);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function providerMatchingValues(): iterable
    {
        return [
            'Same string' => ['"a"', '"a"'],
            'Same integer' => ['1', '1'],
            'Same float' => ['1.2', '1.2'],
            'Both null' => ['null', 'null'],
            'Both true' => ['true', 'true'],
            'Both false' => ['false', 'false'],
            'Same arrays' => ['[1,"a"]', '[1,"a"]'],
            'Same nested arrays' => ['[1,["a"]]', '[1,["a"]]'],
            'Same objects' => ['{"a":"b"}', '{"a":"b"}'],
            'Objects with reversed order of properties' => ['{"a":"b","c":"d"}', '{"c":"d","a":"b"}'],
            'Left object contains additional properties' => ['{"a":"b","c":"d"}', '{"a":"b"}'],
            'Left object contains nested additional properties' => [
                '{"a":{"b":"c","d":"e"},"f":"g"}',
                '{"a":{"b":"c"}}',
            ],
        ];
    }

    /**
     * @param string $data
     * @param string $containedData
     * @dataProvider providerNonMatchingValues
     */
    public function testCompare_NonMatchingValues_ReturnsFalse(string $data, string $containedData): void
    {
        $comparator = new ContainsValueComparator(new Collator('UTF-8'));
        $nodeValueFactory = NodeValueFactory::create();
        $actualValue = $comparator->compare(
            $nodeValueFactory->createValue($data),
            $nodeValueFactory->createValue($containedData)
        );
        self::assertFalse($actualValue);
    }

    /**
     * @return iterable<string, string>
     */
    public static function providerNonMatchingValues(): iterable
    {
        return [
            'Array and scalar' => ['["a"]', '"a"'],
            'Different strings' => ['"a"', '"ab"'],
            'Different integers' => ['1', '2'],
            'Different booleans' => ['true', 'false'],
            'Null and scalar' => ['null', 'false'],
            'Different elements in arrays' => ['[1]', '[2]'],
            'Different number of elements in arrays' => ['[1,2]', '[1]'],
            'Right object contains additional properties' => ['{"a":"b"}', '{"a":"b","c":"d"}'],
            'Object with different property value' => ['{"a":"b"}', '{"a":"c"}'],
        ];
    }

    public function testCompare_DuplicatedPropertyInLeftValue_ReturnsFalse(): void
    {
        $comparator = new ContainsValueComparator(new Collator('UTF-8'));
        $value = $this->createMock(ScalarValueInterface::class);
        $value
            ->method('getData')
            ->willReturn('b');
        $leftValue = $this->createObjectWithDuplicatedProperty('a', $value);
        $rightValue = NodeValueFactory::create()->createValue('{"a":"b"}');
        $actualValue = $comparator->compare($leftValue, $rightValue);
        self::assertFalse($actualValue);
    }

    public function testCompare_DuplicatedPropertyInRightValue_ReturnsFalse(): void
    {
        $comparator = new ContainsValueComparator(new Collator('UTF-8'));
        $leftValue = NodeValueFactory::create()->createValue('{"a":"b"}');
        $value = $this->createMock(ScalarValueInterface::class);
        $value
            ->method('getData')
            ->willReturn('b');
        $rightValue = $this->createObjectWithDuplicatedProperty('a', $value);
        $actualValue = $comparator->compare($leftValue, $rightValue);
        self::assertFalse($actualValue);
    }

    private function createObjectWithDuplicatedProperty(string $name, ValueInterface $value): ValueInterface
    {
        $object = $this->createMock(ObjectValueInterface::class);
        $generator = function () use ($name, $value): Generator {
            yield $name => $value;
            yield $name => $value;
        };
        $object
            ->method('createChildIterator')
            ->willReturn($generator());

        return $object;
    }
}
