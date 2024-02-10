<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Comparator;

use Collator;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Comparator\EqualValueComparator;
use Remorhaz\JSON\Data\Value\EncodedJson\NodeValueFactory;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;
use Remorhaz\JSON\Data\Value\ValueInterface;

#[CoversClass(EqualValueComparator::class)]
class EqualValueComparatorTest extends TestCase
{
    #[DataProvider('providerMatchingValues')]
    public function testCompare_MatchingValues_ReturnsTrue(string $data, string $equalData): void
    {
        $comparator = new EqualValueComparator(new Collator('UTF-8'));
        $nodeValueFactory = NodeValueFactory::create();
        $actualValue = $comparator->compare(
            $nodeValueFactory->createValue($data),
            $nodeValueFactory->createValue($equalData),
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
        ];
    }

    #[DataProvider('providerNonMatchingValues')]
    public function testCompare_NonMatchingValues_ReturnsFalse(string $data, string $equalData): void
    {
        $comparator = new EqualValueComparator(new Collator('UTF-8'));
        $nodeValueFactory = NodeValueFactory::create();
        $actualValue = $comparator->compare(
            $nodeValueFactory->createValue($data),
            $nodeValueFactory->createValue($equalData),
        );
        self::assertFalse($actualValue);
    }

    /**
     * @return iterable<string, array{string, string}>
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
            'Left object contains additional properties' => ['{"a":"b","c":"d"}', '{"a":"b"}'],
            'Right object contains additional properties' => ['{"a":"b"}', '{"a":"b","c":"d"}'],
            'Object with different property value' => ['{"a":"b"}', '{"a":"c"}'],
        ];
    }

    public function testCompare_DuplicatedPropertyInLeftValue_ReturnsFalse(): void
    {
        $comparator = new EqualValueComparator(new Collator('UTF-8'));
        $value = self::createStub(ScalarValueInterface::class);
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
        $comparator = new EqualValueComparator(new Collator('UTF-8'));
        $leftValue = NodeValueFactory::create()->createValue('{"a":"b"}');
        $value = self::createStub(ScalarValueInterface::class);
        $value
            ->method('getData')
            ->willReturn('b');
        $rightValue = $this->createObjectWithDuplicatedProperty('a', $value);
        $actualValue = $comparator->compare($leftValue, $rightValue);
        self::assertFalse($actualValue);
    }

    private function createObjectWithDuplicatedProperty(string $name, ValueInterface $value): ValueInterface
    {
        $object = self::createStub(ObjectValueInterface::class);
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
