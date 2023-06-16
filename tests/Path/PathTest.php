<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Path;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Path\Exception\ParentNotFoundException;
use Remorhaz\JSON\Data\Path\Path;

/**
 * @covers \Remorhaz\JSON\Data\Path\Path
 */
class PathTest extends TestCase
{
    public function testGetElements_ConstructedWithGivenElements_ReturnsSameValues(): void
    {
        $path = new Path('a', 1);
        self::assertSame(['a', 1], $path->getElements());
    }

    public function testCopyWithElement_Constructed_ReturnsNewInstance(): void
    {
        $path = new Path();
        self::assertNotSame($path, $path->copyWithElement(1));
    }

    public function testCopyWithElement_Constructed_ResultContainsMatchingElements(): void
    {
        $path = new Path('a');
        self::assertSame(['a', 1], $path->copyWithElement(1)->getElements());
    }

    public function testCopyWithProperty_Constructed_ReturnsNewInstance(): void
    {
        $path = new Path();
        self::assertNotSame($path, $path->copyWithProperty('a'));
    }

    public function testCopyWithProperty_Constructed_ResultContainsMatchingElements(): void
    {
        $path = new Path('a');
        self::assertSame(['a', 'b'], $path->copyWithProperty('b')->getElements());
    }

    /**
     * @param list<int|string>  $firstElements
     * @param list<int, string> $secondElements
     * @dataProvider providerEquals
     */
    public function testEquals_EqualPath_ReturnsTrue(array $firstElements, array $secondElements): void
    {
        $firstPath = new Path(...$firstElements);
        $secondPath = new Path(...$secondElements);

        self::assertTrue($firstPath->equals($secondPath));
    }

    /**
     * @return iterable<string, array{list<int|string>, list<int|string>}>
     */
    public static function providerEquals(): iterable
    {
        return [
            'Empty paths' => [[], []],
            'Paths with same elements sequence' => [['a', 1], ['a', 1]],
        ];
    }

    /**
     * @param list<int|string> $firstElements
     * @param list<int|string> $secondElements
     * @dataProvider providerNotEquals
     */
    public function testEquals_NotEqualPath_ReturnsFalse(array $firstElements, array $secondElements): void
    {
        $firstPath = new Path(...$firstElements);
        $secondPath = new Path(...$secondElements);

        self::assertFalse($firstPath->equals($secondPath));
    }

    /**
     * @return iterable<string, array{list<int|string>, list<int|string>}>
     */
    public static function providerNotEquals(): iterable
    {
        return [
            'Nested paths' => [['a', 1], ['a']],
            'Paths with different element sequences' => [['a', 1], [1, 'a']],
        ];
    }

    /**
     * @param list<int|string> $pathElements
     * @param list<int|string> $containedPathElements
     * @dataProvider providerContains
     */
    public function testContains_ContainedPath_ReturnsTrue(array $pathElements, array $containedPathElements): void
    {
        $path = new Path(...$pathElements);
        $containedPath = new Path(...$containedPathElements);
        self::assertTrue($path->contains($containedPath));
    }

    /**
     * @return iterable<string, array{list<int|string>, list<int|string>}>
     */
    public static function providerContains(): iterable
    {
        return [
            'Same path' => [['a', 1], ['a', 1]],
            'Sub-path' => [['a'], ['a', 1]],
        ];
    }

    public function testCopyParent_NonEmptyPath_ReturnsNewInstance(): void
    {
        $path = new Path('a');
        $pathCopy = $path->copyParent();
        self::assertNotSame($path, $pathCopy);
    }

    /**
     * @dataProvider providerCopyExistingParent
     * @param list<int|string> $pathElements
     * @param list<int|string> $expectedElements
     */
    public function testCopyParent_NonEmptyPath_ReturnsMatchingPath(array $pathElements, array $expectedElements): void
    {
        $path = new Path(...$pathElements);
        $pathCopy = $path->copyParent();
        self::assertSame($expectedElements, $pathCopy->getElements());
    }

    /**
     * @return iterable<string, array{list<int|string>, list<int|string>}>
     */
    public static function providerCopyExistingParent(): iterable
    {
        return [
            'Single element path' => [['a'], []],
            'Two elements in path' => [['a', 'b'], ['a']],
        ];
    }

    public function testCopyParent_EmptyPath_ThrowsException(): void
    {
        $path = new Path();
        $this->expectException(ParentNotFoundException::class);
        $path->copyParent();
    }
}
