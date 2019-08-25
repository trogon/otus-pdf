<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableRow;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\TableRow
 */
final class TableRowTest extends TestCase
{
    private $tableRowClass = 'trogon\otuspdf\TableRow';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableRowClass,
            new TableRow()
        );
    }
}
