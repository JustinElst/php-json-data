<?php

namespace Remorhaz\JSON\Data\Test\Reference;

use Remorhaz\JSON\Data\Reference\Cursor;

class CursorTest extends \PHPUnit_Framework_TestCase
{


    public function testBoundCursorPointsToDataRoot()
    {
        $data = (object) ['a' => 'b'];
        $cursor = (new Cursor)->bind($data);
        $this->assertEquals($data, $cursor->getDataReference());
    }


    public function testNewCursorReportedNotBound()
    {
        $cursor = new Cursor;
        $this->assertFalse($cursor->isBound());
    }


    public function testCursorReportedBoundAfterBinding()
    {
        $data = (object) ['a' => 'b'];
        $cursor = (new Cursor)->bind($data);
        $this->assertTrue($cursor->isBound());
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testNewCursorThrowsExceptionOnGettingDataReference()
    {
        (new Cursor)->getDataReference();
    }


    /**
     * @expectedException \LogicException
     */
    public function testNewCursorThrowsSplExceptionOnGettingDataReference()
    {
        (new Cursor)->getDataReference();
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testNewCursorThrowsExceptionOnGettingDataCopy()
    {
        (new Cursor)->getDataCopy();
    }


    /**
     * @expectedException \LogicException
     */
    public function testNewCursorThrowsSplExceptionOnGettingDataCopy()
    {
        (new Cursor)->getDataCopy();
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testUnboundCursorThrowsExceptionOnGettingDataReference()
    {
        $data = (object) ['a' => 'b'];
        (new Cursor)->bind($data)->unbind()->getDataReference();
    }


    /**
     * @expectedException \LogicException
     */
    public function testUnboundCursorThrowsSplExceptionOnGettingDataReference()
    {
        $data = (object) ['a' => 'b'];
        (new Cursor)->bind($data)->unbind()->getDataReference();
    }


    /**
     * @expectedException \Remorhaz\JSON\Data\Exception
     */
    public function testUnboundCursorThrowsExceptionOnGettingDataCopy()
    {
        $data = (object) ['a' => 'b'];
        (new Cursor)->bind($data)->unbind()->getDataCopy();
    }


    /**
     * @expectedException \LogicException
     */
    public function testUnboundCursorThrowsSplExceptionOnGettingDataCopy()
    {
        $data = (object) ['a' => 'b'];
        (new Cursor)->bind($data)->unbind()->getDataCopy();
    }


    public function testCursorBindsByReference()
    {
        $data = (object) ['a' => 'b'];
        $cursor = (new Cursor)->bind($data);
        $data->a = 'c';
        $expectedData = clone $data;
        $actualData = $cursor->getDataCopy();
        $this->assertEquals($expectedData, $actualData);
    }


    public function testNoReferenceOnObjectDataCopy()
    {
        $data = (object) ['a' => 'b'];
        $expectedData = clone $data;
        $cursor = (new Cursor)->bind($data);
        $actualData = $cursor->getDataCopy();
        $data->a = 'c';
        $this->assertEquals($expectedData, $actualData);
    }


    public function testNoDataModificationAfterBinding()
    {
        $data = (object) ['a' => 'b'];
        $expectedData = clone $data;
        (new Cursor)->bind($data->a);
        $this->assertEquals($expectedData, $data);
    }


    public function testNoDataModificationAfterUnbinding()
    {
        $data = (object) ['a' => 'b'];
        $expectedData = clone $data;
        (new Cursor)->bind($data->a)->unbind();
        $this->assertEquals($expectedData, $data);
    }
}
