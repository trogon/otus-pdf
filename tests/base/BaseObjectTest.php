<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\base\BaseObject;
use insma\otuspdf\base\InvalidCallException;

final class Dummy extends BaseObject
{
    public $winstonDumpValue;

    public function getRonald() { return 'test ronald'; }
    public function setWinston($value) { $this->winstonDumpValue = $value; }
}

final class BaseObjectTest extends TestCase
{
    private $baseObjectClass = 'insma\otuspdf\base\BaseObject';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->baseObjectClass,
            new Dummy()
        );
    }

    /**
     * @expectedException insma\otuspdf\base\UnknownPropertyException
     */
    public function testCanNotGetValueOnNotExistingProperty()
    {
        $dummy = new Dummy();

        $value = $dummy->dummyProperty;
    }

    public function testIssetReturnsFalseOnNotExistingProperty()
    {
        $dummy = new Dummy();

        $this->assertEquals(
            false,
            isset($dummy->dummyProperty)
        );
    }

    /**
     * @expectedException insma\otuspdf\base\UnknownPropertyException
     */
    public function testCanNotUnsetOnNotExistingProperty()
    {
        $dummy = new Dummy();

        unset($dummy->dummyProperty);
    }

    /**
     * @expectedException insma\otuspdf\base\UnknownPropertyException
     */
    public function testCanNotSetValueOnNotExistingProperty()
    {
        $dummy = new Dummy();

        $dummy->dummyProperty = 'Dummy value';
    }

    /**
     * @expectedException insma\otuspdf\base\UnknownMethodException
     */
    public function testCanNotCallOnNotExistingFunction()
    {
        $dummy = new Dummy();

        $dummy->dummyFunction();
    }

    /**
     * @expectedException insma\otuspdf\base\UnknownMethodException
     */
    public function testCanNotCallStaticOnNotExistingFunction()
    {
        Dummy::dummyStaticFunction();
    }

    public function testCanGetValueOnReadOnlyProperty()
    {
        $dummy = new Dummy();

        $this->assertEquals(
            'test ronald',
            $dummy->ronald
        );
    }

    public function testIssetReturnsTrueOnReadOnlyProperty()
    {
        $dummy = new Dummy();

        $this->assertEquals(
            true,
            isset($dummy->ronald)
        );
    }

    /**
     * @expectedException insma\otuspdf\base\InvalidCallException
     */
    public function testCanNotUnsetOnReadOnlyProperty()
    {
        $dummy = new Dummy();

        unset($dummy->ronald);
    }

    /**
     * @expectedException insma\otuspdf\base\InvalidCallException
     */
    public function testCanNotSetValueOnReadOnlyProperty()
    {
        $dummy = new Dummy();

        $dummy->ronald = 'Dummy value';
    }

    /**
     * @expectedException insma\otuspdf\base\InvalidCallException
     */
    public function testCanNotGetValueOnWriteOnlyProperty()
    {
        $dummy = new Dummy();

        $value = $dummy->winston;
    }

    public function testIssetReturnsFalseOnWriteOnlyProperty()
    {
        $dummy = new Dummy();

        $this->assertEquals(
            false,
            isset($dummy->winston)
        );
    }

    /**
     * @expectedException insma\otuspdf\base\UnknownPropertyException
     */
    public function testCanNotUnsetOnWriteOnlyProperty()
    {
        $dummy = new Dummy();

        unset($dummy->winston);
    }

    public function testCanSetValueOnWriteOnlyProperty()
    {
        $dummy = new Dummy();

        $dummy->winston = 'Dummy value';

        $this->assertEquals(
            'Dummy value',
            $dummy->winstonDumpValue
        );
    }
}
