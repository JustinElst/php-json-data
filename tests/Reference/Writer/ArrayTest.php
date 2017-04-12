<?php

namespace Remorhaz\JSON\Data\Test\Reference\Writer;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Reference\Writer;

class ArrayTest extends TestCase
{


    /**
     * @param array $data
     * @param int $index
     * @param mixed $expectedData
     * @dataProvider providerExistingIndex
     */
    public function testCorrectDataAfterSelectingExistingIndex(array $data, int $index, $expectedData)
    {
        $actualData = (new Writer($data))
            ->selectElement($index)
            ->getAsStruct();
        $this->assertEquals($expectedData, $actualData);
    }


    /**
     * @param array $data
     * @param int $index
     * @dataProvider providerExistingIndex
     */
    public function testHasDataAfterSelectingExistingIndex(array $data, int $index)
    {
        $hasData = (new Writer($data))
            ->selectElement($index)
            ->hasData();
        $this->assertTrue($hasData);
    }


    public function providerExistingIndex(): array
    {
        return [
            [['a', 'b', 'c'], 1, 'b'],
        ];
    }


    /**
     * @param array $data
     * @param int $index
     * @dataProvider providerNonExistingIndex
     */
    public function testHasNoDataAfterSelectingNonExistingIndex(array $data, int $index)
    {
        $hasData = (new Writer($data))
            ->selectElement($index)
            ->hasData();
        $this->assertFalse($hasData);
    }


    /**
     * @param array $data
     * @param int $index
     * @dataProvider providerNonExistingIndex
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testExceptionOnDataAcessAfterSelectingNonExistingIndex(array $data, int $index)
    {
        (new Writer($data))
            ->selectElement($index)
            ->getAsStruct();
    }


    /**
     * @param array $data
     * @param int $index
     * @dataProvider providerNonExistingIndex
     * @expectedException \LogicException
     */
    public function testSplExceptionOnDataAcessAfterSelectingNonExistingIndex(array $data, int $index)
    {
        (new Writer($data))
            ->selectElement($index)
            ->getAsStruct();
    }


    public function providerNonExistingIndex(): array
    {
        return [
            [['a', 'b'], 2, 'c'],
        ];
    }


    /**
     * @param mixed $data
     * @param int $index
     * @expectedException \Remorhaz\JSON\Data\Exception
     * @dataProvider providerNonArrayData
     */
    public function testExceptionOnNonArrayIndexSelection($data, int $index)
    {
        (new Writer($data))->selectElement($index);
    }


    /**
     * @param mixed $data
     * @param int $index
     * @expectedException \LogicException
     * @dataProvider providerNonArrayData
     */
    public function testSplExceptionOnNonArrayIndexSelection($data, int $index)
    {
        (new Writer($data))->selectElement($index);
    }


    /**
     * @param mixed $data
     * @expectedException \Remorhaz\JSON\Data\Exception
     * @dataProvider providerNonArrayData
     */
    public function testExceptionOnNonArrayNewIndexSelection($data)
    {
        (new Writer($data))->selectNewElement();
    }


    /**
     * @param mixed $data
     * @expectedException \LogicException
     * @dataProvider providerNonArrayData
     */
    public function testSplExceptionOnNonArrayNewIndexSelection($data)
    {
        (new Writer($data))->selectNewElement();
    }


    /**
     * @param mixed $data
     * @dataProvider providerNonArrayData
     */
    public function testNoArraySelectedAfterCreationWithNonArrayData($data)
    {
        $isArraySelected = (new Writer($data))->isArray();
        $this->assertFalse($isArraySelected);
    }


    public function providerNonArrayData(): array
    {
        return [
            'objectData' => [(object) [1 => 'a'], 1],
            'booleanData' => [true, 1],
            'nullData' => [null, 1],
            'integerData' => [1, 1],
            'floatData' => [1.2, 1],
        ];
    }


    /**
     * @param array $data
     * @dataProvider providerArrayData
     */
    public function testCorrectlyReportedNonSelectedIndexInArrayData(array $data)
    {
        $isIndexSelected = (new Writer($data))->isElement();
        $this->assertFalse($isIndexSelected);
    }


    /**
     * @param array $data
     * @dataProvider providerArrayData
     */
    public function testArrayIsSelectedAfterCreationWithArrayData(array $data)
    {
        $isArraySelected = (new Writer($data))->isArray();
        $this->assertTrue($isArraySelected);
    }


    public function providerArrayData(): array
    {
        return [
            [['a', 'b', 'c']],
        ];
    }


    /**
     * @param array $data
     * @param int $index
     * @param mixed $newValue
     * @param array $expectedData
     * @dataProvider providerReplacingExistingIndex
     */
    public function testCorrectDataAfterReplacingExistingIndex(
        array $data,
        int $index,
        $newValue,
        array $expectedData
    ) {
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectElement($index)
            ->replaceData($newValueReader);
        $this->assertEquals($expectedData, $data);
    }


    public function providerReplacingExistingIndex(): array
    {
        return [
            [['a', 'b', 'c'], 1, 'd', ['a', 'd', 'c']],
        ];
    }


    public function testIndexRemainsSelectedAfterReplacingExistingIndex()
    {
        $data = ['a', 'b', 'c'];
        $expectedData = $data;
        $index = 1;
        $newValue= 'd';
        $newValueReader = new Writer($newValue);
        $oldValue = 'b';
        $oldValueReader = new Writer($oldValue);
        (new Writer($data))
            ->selectElement($index)
            ->replaceData($newValueReader)
            ->replaceData($oldValueReader);
        $this->assertEquals($expectedData, $data);
    }


    public function testCorrectDataAfterAppendElement()
    {
        $data = ['a', 'b'];
        $newValue = 'c';
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectNewElement()
            ->appendElement($newValueReader);
        $expectedData =  ['a', 'b', 'c'];
        $this->assertEquals($expectedData, $data);
    }


    public function testNewIndexRemainsSelectedAfterAppendingElement()
    {
        $data = ['a', 'b'];
        $newValue = 'c';
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectNewElement()
            ->appendElement($newValueReader)
            ->appendElement($newValueReader);
        $expectedData =  ['a', 'b', 'c', 'c'];
        $this->assertEquals($expectedData, $data);
    }


    public function testCorrectDataAfterRemovingElement()
    {
        $data = ['a', 'b', 'c'];
        $index = 1;
        (new Writer($data))
            ->selectElement($index)
            ->removeElement();
        $expectedData = ['a', 'c'];
        $this->assertEquals($expectedData, $data);
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testExceptionOnReplaceDataAfterRemovingElement()
    {
        $data = ['a', 'b', 'c'];
        $index = 1;
        $newValue = 'd';
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectElement($index)
            ->removeElement()
            ->replaceData($newValueReader);
    }


    /**
     * @expectedException \LogicException
     */
    public function testSplExceptionOnReplaceDataAfterRemovingElement()
    {
        $data = ['a', 'b', 'c'];
        $index = 1;
        $newValue = 'd';
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectElement($index)
            ->removeElement()
            ->replaceData($newValueReader);
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testExceptionOnRemovingNonExistingElement()
    {
        $data = ['a', 'b'];
        $index = 2;
        (new Writer($data))
            ->selectElement($index)
            ->removeElement();
    }


    /**
     * @expectedException \LogicException
     */
    public function testSplExceptionOnRemovingNonExistingElement()
    {
        $data = ['a', 'b'];
        $index = 2;
        (new Writer($data))
            ->selectElement($index)
            ->removeElement();
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testExceptionOnRemovingNonSelectedElement()
    {
        $data = ['a', 'b'];
        (new Writer($data))->removeElement();
    }


    /**
     * @expectedException \LogicException
     */
    public function testSplExceptionOnRemovingNonSelectedElement()
    {
        $data = ['a', 'b'];
        (new Writer($data))->removeElement();
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testExceptionOnAppendingNonSelectedNewElement()
    {
        $data = ['a', 'b'];
        $newValue = 'c';
        $newValueReader = new Writer($newValue);
        (new Writer($data))->appendElement($newValueReader);
    }


    /**
     * @expectedException \LogicException
     */
    public function testSplExceptionOnAppendingNonSelectedNewElement()
    {
        $data = ['a', 'b'];
        $newValue = 'c';
        $newValueReader = new Writer($newValue);
        (new Writer($data))->appendElement($newValueReader);
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testExceptionOnAppendingSelectedExistingElement()
    {
        $data = ['a', 'b', 'c'];
        $index = 1;
        $newValue = 'd';
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectElement($index)
            ->appendElement($newValueReader);
    }


    /**
     * @expectedException \LogicException
     */
    public function testSplExceptionOnAppendingSelectedExistingElement()
    {
        $data = ['a', 'b', 'c'];
        $index = 1;
        $newValue = 'd';
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectElement($index)
            ->appendElement($newValueReader);
    }


    public function testInsertElement_ElementExists_Inserted()
    {
        $data = ['a', 'c'];
        $newValue = 'b';
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectElement(1)
            ->insertElement($newValueReader);
        $expectedData = ['a', 'b', 'c'];
        $this->assertEquals($expectedData, $data);
    }


    public function testInsertElement_ElementExists_DataReferencesInsertedElement()
    {
        $data = ['a', 'c'];
        $newValue = 'b';
        $newValueReader = new Writer($newValue);
        $actualData = (new Writer($data))
            ->selectElement(1)
            ->insertElement($newValueReader)
            ->getAsStruct();
        $this->assertEquals('b', $actualData);
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     * @expectedExceptionMessageRegExp /^Following element must be selected before insertion$/
     */
    public function testInsertElement_ElementNotExusts_ThrowsException()
    {
        $data = ['a', 'b'];
        $newValue = 'c';
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectElement(2)
            ->insertElement($newValueReader);
    }


    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp /^Following element must be selected before insertion$/
     */
    public function testInsertElement_ElementNotExusts_ThrowsSplException()
    {
        $data = ['a', 'b'];
        $newValue = 'c';
        $newValueReader = new Writer($newValue);
        (new Writer($data))
            ->selectElement(2)
            ->insertElement($newValueReader);
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     * @expectedExceptionMessageRegExp /^Index must be selected before element insertion$/
     */
    public function testInsertElement_IndexNotSelected_ThrowsException()
    {
        $data = ['a', 'b'];
        $newValue = 'c';
        $newValueReader = new Writer($newValue);
        (new Writer($data))->insertElement($newValueReader);
    }


    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp /^Index must be selected before element insertion$/
     */
    public function testInsertElement_IndexNotSelected_ThrowsSplException()
    {
        $data = ['a', 'b'];
        $newValue = 'c';
        $newValueReader = new Writer($newValue);
        (new Writer($data))->insertElement($newValueReader);
    }
}
