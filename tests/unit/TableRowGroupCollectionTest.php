<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableRowGroup;
use trogon\otuspdf\TableRowGroupCollection;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\TableRowGroupCollection
 */
final class TableRowGroupCollectionTest extends TestCase
{
    private $tableRowGroupCollectionClass = 'trogon\otuspdf\TableRowGroupCollection';
    private $tableRowGroupClass = 'trogon\otuspdf\TableRowGroup';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableRowGroupCollectionClass,
            new TableRowGroupCollection()
        );
    }

    public function testAddReturnsTableRowGroup()
    {
        $collection = new TableRowGroupCollection();
        $this->assertInstanceOf(
            $this->tableRowGroupClass,
            $collection->add(new TableRowGroup())
        );
    }

    public function testContainsTableRowGroupOnNotAddedIsFalse()
    {
        $myItem = new TableRowGroup();

        $collection = new TableRowGroupCollection();
        $collection->add(new TableRowGroup());
        $collection->add(new TableRowGroup());
        $collection->add(new TableRowGroup());

        $this->assertEquals(false, $collection->contains($myItem));
    }

    public function testContainsTableRowGroupOnAddedIsTrue()
    {
        $myItem = new TableRowGroup();

        $collection = new TableRowGroupCollection();
        $collection->add(new TableRowGroup());
        $collection->add($myItem);
        $collection->add(new TableRowGroup());

        $this->assertEquals(true, $collection->contains($myItem));
    }

    public function testRemoveTableRowGroupOnNotAddedIsFalse()
    {
        $myItem = new TableRowGroup();

        $collection = new TableRowGroupCollection();
        $collection->add(new TableRowGroup());
        $collection->add(new TableRowGroup());
        $collection->add(new TableRowGroup());

        $this->assertEquals(false, $collection->remove($myItem));
    }

    public function testRemoveTableRowGroupOnAddedIsTrue()
    {
        $myItem = new TableRowGroup();

        $collection = new TableRowGroupCollection();
        $collection->add(new TableRowGroup());
        $collection->add($myItem);
        $collection->add(new TableRowGroup());

        $this->assertEquals(true, $collection->remove($myItem));
    }

    public function testCountTableRowGroupsOnEmpty()
    {
        $collection = new TableRowGroupCollection();

        $this->assertEquals(0, count($collection));
    }

    public function testCountOnNotEmpty()
    {
        $collection = new TableRowGroupCollection();
        $collection->add(new TableRowGroup());
        $collection->add(new TableRowGroup());
        $collection->add(new TableRowGroup());

        $this->assertEquals(3, count($collection));
    }

    public function testForeachTableRowGroupsOnEmpty()
    {
        $collection = new TableRowGroupCollection();

        foreach ($collection as $tableRowGroup) {
            $this->assertInstanceOf(
                $this->tableRowGroupClass,
                $tableRowGroup
            );
        }

        $this->assertInstanceOf(
            $this->tableRowGroupCollectionClass,
            new TableRowGroupCollection()
        );
    }

    public function testForeachTableRowGroupsOnNotEmpty()
    {
        $collection = new TableRowGroupCollection();
        $expectedTableRowGroups = [];
        $expectedTableRowGroups[] = $collection->add(new TableRowGroup());
        $expectedTableRowGroups[] = $collection->add(new TableRowGroup());

        $tableRowGroups = [];
        foreach ($collection as $key => $tableRowGroup) {
            $this->assertInstanceOf(
                $this->tableRowGroupClass,
                $tableRowGroup
            );
            $tableRowGroups[] = $tableRowGroup;
        }

        $this->assertEquals($expectedTableRowGroups, $tableRowGroups);
    }
}
