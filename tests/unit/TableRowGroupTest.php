<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TableRowGroup;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\TableRowGroup
 */
final class TableRowGroupTest extends TestCase
{
    private $tableRowGroupClass = 'trogon\otuspdf\TableRowGroup';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableRowGroupClass,
            new TableRowGroup()
        );
    }
}
