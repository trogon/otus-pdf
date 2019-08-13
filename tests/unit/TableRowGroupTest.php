<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableRowGroup;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\TableRowGroup
 */
final class TableRowGroupTest extends TestCase
{
    private $tableRowGroupClass = 'trogon\otuspdf\TableRowGroup';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableRowGroupClass,
            new TableRowGroup()
        );
    }
}
