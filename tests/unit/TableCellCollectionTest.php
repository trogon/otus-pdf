<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableCell;
use trogon\otuspdf\TableCellCollection;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\TableCellCollection
 */
final class TableCellCollectionTest extends TestCase
{
    private $tableCellCollectionClass = 'trogon\otuspdf\TableCellCollection';
    private $tableCellClass = 'trogon\otuspdf\TableCell';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableCellCollectionClass,
            new TableCellCollection()
        );
    }

    public function testAddReturnsTableCell()
    {
        $collection = new TableCellCollection();
        $this->assertInstanceOf(
            $this->tableCellClass,
            $collection->add(new TableCell())
        );
    }

    public function testContainsTableCellOnNotAddedIsFalse()
    {
        $myItem = new TableCell();

        $collection = new TableCellCollection();
        $collection->add(new TableCell());
        $collection->add(new TableCell());
        $collection->add(new TableCell());

        $this->assertEquals(false, $collection->contains($myItem));
    }

    public function testContainsTableCellOnAddedIsTrue()
    {
        $myItem = new TableCell();

        $collection = new TableCellCollection();
        $collection->add(new TableCell());
        $collection->add($myItem);
        $collection->add(new TableCell());

        $this->assertEquals(true, $collection->contains($myItem));
    }

    public function testRemoveTableCellOnNotAddedIsFalse()
    {
        $myItem = new TableCell();

        $collection = new TableCellCollection();
        $collection->add(new TableCell());
        $collection->add(new TableCell());
        $collection->add(new TableCell());

        $this->assertEquals(false, $collection->remove($myItem));
    }

    public function testRemoveTableCellOnAddedIsTrue()
    {
        $myItem = new TableCell();

        $collection = new TableCellCollection();
        $collection->add(new TableCell());
        $collection->add($myItem);
        $collection->add(new TableCell());

        $this->assertEquals(true, $collection->remove($myItem));
    }

    public function testCountTableCellsOnEmpty()
    {
        $collection = new TableCellCollection();

        $this->assertEquals(0, count($collection));
    }

    public function testCountOnNotEmpty()
    {
        $collection = new TableCellCollection();
        $collection->add(new TableCell());
        $collection->add(new TableCell());
        $collection->add(new TableCell());

        $this->assertEquals(3, count($collection));
    }

    public function testForeachTableCellsOnEmpty()
    {
        $collection = new TableCellCollection();

        foreach ($collection as $tableCell) {
            $this->assertInstanceOf(
                $this->tableCellClass,
                $tableCell
            );
        }

        $this->assertInstanceOf(
            $this->tableCellCollectionClass,
            new TableCellCollection()
        );
    }

    public function testForeachTableCellsOnNotEmpty()
    {
        $collection = new TableCellCollection();
        $expectedTableCells = [];
        $expectedTableCells[] = $collection->add(new TableCell());
        $expectedTableCells[] = $collection->add(new TableCell());

        $tableCells = [];
        foreach ($collection as $key => $tableCell) {
            $this->assertInstanceOf(
                $this->tableCellClass,
                $tableCell
            );
            $tableCells[] = $tableCell;
        }

        $this->assertEquals($expectedTableCells, $tableCells);
    }
}
