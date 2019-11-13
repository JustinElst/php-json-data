<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Comparator;

use Collator;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Comparator\GreaterValueComparator;
use Remorhaz\JSON\Data\Value\EncodedJson\NodeValueFactory;

/**
 * @covers \Remorhaz\JSON\Data\Comparator\GreaterValueComparator
 */
class GreaterValueComparatorTest extends TestCase
{

    /**
     * @param string $leftData
     * @param string $rightData
     * @dataProvider providerMatchingValues
     */
    public function testCompare_MatchingValues_ReturnsTrue(string $leftData, string $rightData): void
    {
        $comparator = new GreaterValueComparator(new Collator('UTF-8'));
        $nodeValueFactory = NodeValueFactory::create();
        $actualValue = $comparator->compare(
            $nodeValueFactory->createValue($leftData),
            $nodeValueFactory->createValue($rightData)
        );
        self::assertTrue($actualValue);
    }

    public function providerMatchingValues(): array
    {
        return [
            'Left string is greater' => ['"b"', '"a"'],
            'Left non-ASCII string is greater' => ['"в"', '"б"'],
            'Left integer is greater' => ['2', '1'],
            'Left float is greater' => ['1.23', '1.2'],
        ];
    }

    /**
     * @param string $leftData
     * @param string $rightData
     * @dataProvider providerNonMatchingValues
     */
    public function testCompare_NonMatchingValues_ReturnsFalse(string $leftData, string $rightData): void
    {
        $comparator = new GreaterValueComparator(new Collator('UTF-8'));
        $nodeValueFactory = NodeValueFactory::create();
        $actualValue = $comparator->compare(
            $nodeValueFactory->createValue($leftData),
            $nodeValueFactory->createValue($rightData)
        );
        self::assertFalse($actualValue);
    }

    public function providerNonMatchingValues(): array
    {
        return [
            'Same string' => ['"a"', '"b"'],
            'Left string is lesser' => ['"a"', '"b"'],
            'Same non-ASCII strings' => ['"б"', '"б"'],
            'Left non-ASCII string is lesser' => ['"б"', '"в"'],
            'Same integers' => ['1', '1'],
            'Left integer is lesser' => ['1', '2'],
            'Same floats' => ['1.2', '1.2'],
            'Left float is lesser' => ['1.2', '1.23'],
            'One value is null' => ['1', 'null'],
            'One value is array' => ['2', '[1]'],
            'One value is object' => ['2', '{"1":2}'],
        ];
    }
}
