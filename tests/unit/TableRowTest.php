<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableRow;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\TableRow
 */
final class TableRowTest extends TestCase
{
    private $tableRowClass = 'trogon\otuspdf\TableRow';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableRowClass,
            new TableRow()
        );
    }
}
