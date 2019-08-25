<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableColumn;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\TableColumn
 */
final class TableColumnTest extends TestCase
{
    private $tableColumnClass = 'trogon\otuspdf\TableColumn';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableColumnClass,
            new TableColumn()
        );
    }
}
