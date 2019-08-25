<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Table;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\Table
 */
final class TableTest extends TestCase
{
    private $tableClass = 'trogon\otuspdf\Table';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableClass,
            new Table()
        );
    }
}
