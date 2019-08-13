<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableColumn;
use trogon\otuspdf\TableColumnCollection;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\TableColumnCollection
 */
final class TableColumnCollectionTest extends TestCase
{
    private $tableColumnCollectionClass = 'trogon\otuspdf\TableColumnCollection';
    private $tableColumnClass = 'trogon\otuspdf\TableColumn';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableColumnCollectionClass,
            new TableColumnCollection()
        );
    }

    public function testAddReturnsTableColumn()
    {
        $collection = new TableColumnCollection();
        $this->assertInstanceOf(
            $this->tableColumnClass,
            $collection->add(new TableColumn())
        );
    }

    public function testContainsTableColumnOnNotAddedIsFalse()
    {
        $myItem = new TableColumn();

        $collection = new TableColumnCollection();
        $collection->add(new TableColumn());
        $collection->add(new TableColumn());
        $collection->add(new TableColumn());

        $this->assertEquals(false, $collection->contains($myItem));
    }

    public function testContainsTableColumnOnAddedIsTrue()
    {
        $myItem = new TableColumn();

        $collection = new TableColumnCollection();
        $collection->add(new TableColumn());
        $collection->add($myItem);
        $collection->add(new TableColumn());

        $this->assertEquals(true, $collection->contains($myItem));
    }

    public function testRemoveTableColumnOnNotAddedIsFalse()
    {
        $myItem = new TableColumn();

        $collection = new TableColumnCollection();
        $collection->add(new TableColumn());
        $collection->add(new TableColumn());
        $collection->add(new TableColumn());

        $this->assertEquals(false, $collection->remove($myItem));
    }

    public function testRemoveTableColumnOnAddedIsTrue()
    {
        $myItem = new TableColumn();

        $collection = new TableColumnCollection();
        $collection->add(new TableColumn());
        $collection->add($myItem);
        $collection->add(new TableColumn());

        $this->assertEquals(true, $collection->remove($myItem));
    }

    public function testCountTableColumnsOnEmpty()
    {
        $collection = new TableColumnCollection();

        $this->assertEquals(0, count($collection));
    }

    public function testCountOnNotEmpty()
    {
        $collection = new TableColumnCollection();
        $collection->add(new TableColumn());
        $collection->add(new TableColumn());
        $collection->add(new TableColumn());

        $this->assertEquals(3, count($collection));
    }

    public function testForeachTableColumnsOnEmpty()
    {
        $collection = new TableColumnCollection();

        foreach ($collection as $tableColumn) {
            $this->assertInstanceOf(
                $this->tableColumnClass,
                $tableColumn
            );
        }

        $this->assertInstanceOf(
            $this->tableColumnCollectionClass,
            new TableColumnCollection()
        );
    }

    public function testForeachTableColumnsOnNotEmpty()
    {
        $collection = new TableColumnCollection();
        $expectedTableColumns = [];
        $expectedTableColumns[] = $collection->add(new TableColumn());
        $expectedTableColumns[] = $collection->add(new TableColumn());

        $tableColumns = [];
        foreach ($collection as $key => $tableColumn) {
            $this->assertInstanceOf(
                $this->tableColumnClass,
                $tableColumn
            );
            $tableColumns[] = $tableColumn;
        }

        $this->assertEquals($expectedTableColumns, $tableColumns);
    }
}
