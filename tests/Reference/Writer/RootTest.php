<?php

namespace Remorhaz\JSON\Data\Test\Reference\Writer;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Reference\Writer;

class RootTest extends TestCase
{


    /**
     * @param mixed $data
     * @dataProvider providerRootData
     */
    public function testCorrectDataAfterCreation($data)
    {
        $actualData = (new Writer($data))->getAsStruct();
        $this->assertEquals($data, $actualData);
    }


    /**
     * @param mixed $data
     * @dataProvider providerRootData
     */
    public function testHasDataAfterCreation($data)
    {
        $hasData = (new Writer($data))->hasData();
        $this->assertTrue($hasData);
    }


    public function providerRootData(): array
    {
        return [
            'scalarData' => [1],
            'structData' => [[0, 1]],
        ];
    }


    /**
     * @param mixed $sourceData
     * @param mixed $targetData
     * @dataProvider providerReplaceData
     */
    public function testCorrectDataAfterReplace($sourceData, $targetData)
    {
        $expectedData = $sourceData;
        $sourceRaw = new Writer($sourceData);
        $actualData = (new Writer($targetData))->replaceData($sourceRaw)->getAsStruct();
        $this->assertEquals($expectedData, $actualData);
    }


    /**
     * @param mixed $sourceData
     * @param mixed $targetData
     * @dataProvider providerReplaceData
     */
    public function testHasDataAfterReplace($sourceData, $targetData)
    {
        $sourceRaw = new Writer($sourceData);
        $hasData = (new Writer($targetData))->replaceData($sourceRaw)->hasData();
        $this->assertTrue($hasData);
    }


    public function providerReplaceData(): array
    {
        return [
            'scalarData' => [1, 'abc'],
            'structData' => [[0, 1], (object) ['a' => 'b']],
        ];
    }


    /**
     * @param array $data
     * @param int $expectedValue
     * @dataProvider providerArrayElementCount
     */
    public function testGetElementCount_ArraySelected_Calculated(array $data, int $expectedValue)
    {
        $actualValue = (new Writer($data))->getElementCount();
        $this->assertEquals($expectedValue, $actualValue);
    }


    public function providerArrayElementCount(): array
    {
        return [
            'emptyArray' => [[], 0],
            'nonEmptyArray' => [[1, 2, 3], 3],
        ];
    }


    /**
     * @param mixed $data
     * @dataProvider providerNonArrayData
     * @expectedException \Remorhaz\JSON\Data\Exception
     * @expectedExceptionMessageRegExp /^Cursor should point an array to get elements count$/
     */
    public function testGetElementCount_ArrayNotSelected_ExceptionThrown($data)
    {
        (new Writer($data))->getElementCount();
    }


    /**
     * @param mixed $data
     * @dataProvider providerNonArrayData
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp /^Cursor should point an array to get elements count$/
     */
    public function testGetElementCount_ArrayNotSelected_SplExceptionThrown($data)
    {
        (new Writer($data))->getElementCount();
    }


    public function providerNonArrayData(): array
    {
        return [
            'object' => [(object) ['a' => 'b']],
            'integer' => [1],
            'float' => [1.2],
            'boolean' => [true],
            'null' => [null],
        ];
    }
}