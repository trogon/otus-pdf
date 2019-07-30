<?php
namespace trogon\otuspdf\test\unit\base;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\DependencyObject;
use trogon\otuspdf\base\InvalidCallException;

use trogon\otuspdf\test\fixture\DependencyObjectDummy;

/**
 * @covers \trogon\otuspdf\base\DependencyObject
 */
final class DependencyObjectTest extends TestCase
{
    private $dependencyObjectClass = 'trogon\otuspdf\base\DependencyObject';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->dependencyObjectClass,
            new DependencyObjectDummy()
        );
    }

    /**
     * @expectedException trogon\otuspdf\base\UnknownPropertyException
     */
    public function testCanNotGetValueOnNotExistingProperty()
    {
        $dummy = new DependencyObjectDummy();

        $value = $dummy->dummyProperty;
    }

    public function testIssetReturnsFalseOnNotExistingProperty()
    {
        $dummy = new DependencyObjectDummy();

        $this->assertEquals(
            false,
            isset($dummy->dummyProperty)
        );
    }

    /**
     * @expectedException trogon\otuspdf\base\UnknownPropertyException
     */
    public function testCanNotUnsetOnNotExistingProperty()
    {
        $dummy = new DependencyObjectDummy();

        unset($dummy->dummyProperty);
    }

    /**
     * @expectedException trogon\otuspdf\base\UnknownPropertyException
     */
    public function testCanNotSetValueOnNotExistingProperty()
    {
        $dummy = new DependencyObjectDummy();

        $dummy->dummyProperty = 'Dummy value';
    }

    /**
     * @expectedException trogon\otuspdf\base\UnknownMethodException
     */
    public function testCanNotCallOnNotExistingFunction()
    {
        $dummy = new DependencyObjectDummy();

        $dummy->dummyFunction();
    }

    /**
     * @expectedException trogon\otuspdf\base\UnknownMethodException
     */
    public function testCanNotCallStaticOnNotExistingFunction()
    {
        DependencyObjectDummy::dummyStaticFunction();
    }

    public function testCanGetValueOnReadOnlyProperty()
    {
        $dummy = new DependencyObjectDummy();

        $this->assertEquals(
            'test ronald',
            $dummy->ronald
        );
    }

    public function testIssetReturnsTrueOnReadOnlyProperty()
    {
        $dummy = new DependencyObjectDummy();

        $this->assertEquals(
            true,
            isset($dummy->ronald)
        );
    }

    /**
     * @expectedException trogon\otuspdf\base\InvalidCallException
     */
    public function testCanNotUnsetOnReadOnlyProperty()
    {
        $dummy = new DependencyObjectDummy();

        unset($dummy->ronald);
    }

    /**
     * @expectedException trogon\otuspdf\base\InvalidCallException
     */
    public function testCanNotSetValueOnReadOnlyProperty()
    {
        $dummy = new DependencyObjectDummy();

        $dummy->ronald = 'Dummy value';
    }

    /**
     * @expectedException trogon\otuspdf\base\InvalidCallException
     */
    public function testCanNotGetValueOnWriteOnlyProperty()
    {
        $dummy = new DependencyObjectDummy();

        $value = $dummy->winston;
    }

    public function testIssetReturnsFalseOnWriteOnlyProperty()
    {
        $dummy = new DependencyObjectDummy();

        $this->assertEquals(
            false,
            isset($dummy->winston)
        );
    }

    /**
     * @expectedException trogon\otuspdf\base\UnknownPropertyException
     */
    public function testCanNotUnsetOnWriteOnlyProperty()
    {
        $dummy = new DependencyObjectDummy();

        unset($dummy->winston);
    }

    public function testCanSetValueOnWriteOnlyProperty()
    {
        $dummy = new DependencyObjectDummy();

        $dummy->winston = 'Dummy value';

        $this->assertEquals(
            'Dummy value',
            $dummy->winstonDumpValue
        );
    }

    public function testToDictionary()
    {
        $config = [
            'winstonDumpValue' => 'Dummy value'
        ];
        $dummy = new DependencyObjectDummy($config);

        $this->assertEquals(
            $config,
            $dummy->toDictionary()
        );
    }
}
