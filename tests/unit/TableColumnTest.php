<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableColumn;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\TableColumn
 */
final class TableColumnTest extends TestCase
{
    private $tableColumnClass = 'trogon\otuspdf\TableColumn';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableColumnClass,
            new TableColumn()
        );
    }
}
