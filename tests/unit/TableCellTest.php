<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableCell;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\TableCell
 */
final class TableCellTest extends TestCase
{
    private $tableCellClass = 'trogon\otuspdf\TableCell';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableCellClass,
            new TableCell()
        );
    }
}
