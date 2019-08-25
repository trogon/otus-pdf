<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableRow;
use trogon\otuspdf\TableRowCollection;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\TableRowCollection
 */
final class TableRowCollectionTest extends TestCase
{
    private $tableRowCollectionClass = 'trogon\otuspdf\TableRowCollection';
    private $tableRowClass = 'trogon\otuspdf\TableRow';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableRowCollectionClass,
            new TableRowCollection()
        );
    }

    public function testAddReturnsTableRow()
    {
        $collection = new TableRowCollection();
        $this->assertInstanceOf(
            $this->tableRowClass,
            $collection->add(new TableRow())
        );
    }

    public function testContainsTableRowOnNotAddedIsFalse()
    {
        $myItem = new TableRow();

        $collection = new TableRowCollection();
        $collection->add(new TableRow());
        $collection->add(new TableRow());
        $collection->add(new TableRow());

        $this->assertEquals(false, $collection->contains($myItem));
    }

    public function testContainsTableRowOnAddedIsTrue()
    {
        $myItem = new TableRow();

        $collection = new TableRowCollection();
        $collection->add(new TableRow());
        $collection->add($myItem);
        $collection->add(new TableRow());

        $this->assertEquals(true, $collection->contains($myItem));
    }

    public function testRemoveTableRowOnNotAddedIsFalse()
    {
        $myItem = new TableRow();

        $collection = new TableRowCollection();
        $collection->add(new TableRow());
        $collection->add(new TableRow());
        $collection->add(new TableRow());

        $this->assertEquals(false, $collection->remove($myItem));
    }

    public function testRemoveTableRowOnAddedIsTrue()
    {
        $myItem = new TableRow();

        $collection = new TableRowCollection();
        $collection->add(new TableRow());
        $collection->add($myItem);
        $collection->add(new TableRow());

        $this->assertEquals(true, $collection->remove($myItem));
    }

    public function testCountTableRowsOnEmpty()
    {
        $collection = new TableRowCollection();

        $this->assertEquals(0, count($collection));
    }

    public function testCountOnNotEmpty()
    {
        $collection = new TableRowCollection();
        $collection->add(new TableRow());
        $collection->add(new TableRow());
        $collection->add(new TableRow());

        $this->assertEquals(3, count($collection));
    }

    public function testForeachTableRowsOnEmpty()
    {
        $collection = new TableRowCollection();

        foreach ($collection as $tableRow) {
            $this->assertInstanceOf(
                $this->tableRowClass,
                $tableRow
            );
        }

        $this->assertInstanceOf(
            $this->tableRowCollectionClass,
            new TableRowCollection()
        );
    }

    public function testForeachTableRowsOnNotEmpty()
    {
        $collection = new TableRowCollection();
        $expectedTableRows = [];
        $expectedTableRows[] = $collection->add(new TableRow());
        $expectedTableRows[] = $collection->add(new TableRow());

        $tableRows = [];
        foreach ($collection as $key => $tableRow) {
            $this->assertInstanceOf(
                $this->tableRowClass,
                $tableRow
            );
            $tableRows[] = $tableRow;
        }

        $this->assertEquals($expectedTableRows, $tableRows);
    }
}
