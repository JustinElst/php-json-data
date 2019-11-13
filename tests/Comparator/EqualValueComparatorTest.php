<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Comparator;

use Collator;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Comparator\EqualValueComparator;
use Remorhaz\JSON\Data\Value\EncodedJson\NodeValueFactory;

/**
 * @covers \Remorhaz\JSON\Data\Comparator\EqualValueComparator
 */
class EqualValueComparatorTest extends TestCase
{

    /**
     * @param string $data
     * @param string $equalData
     * @dataProvider providerMatchingValues
     */
    public function testCompare_MatchingValues_ReturnsTrue(string $data, string $equalData): void
    {
        $comparator = new EqualValueComparator(new Collator('UTF-8'));
        $nodeValueFactory = NodeValueFactory::create();
        $actualValue = $comparator->compare(
            $nodeValueFactory->createValue($data),
            $nodeValueFactory->createValue($equalData)
        );
        self::assertTrue($actualValue);
    }

    public function providerMatchingValues(): array
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

    /**
     * @param string $data
     * @param string $equalData
     * @dataProvider providerNonMatchingValues
     */
    public function testCompare_NonMatchingValues_ReturnsFalse(string $data, string $equalData): void
    {
        $comparator = new EqualValueComparator(new Collator('UTF-8'));
        $nodeValueFactory = NodeValueFactory::create();
        $actualValue = $comparator->compare(
            $nodeValueFactory->createValue($data),
            $nodeValueFactory->createValue($equalData)
        );
        self::assertFalse($actualValue);
    }

    public function providerNonMatchingValues(): array
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
}
