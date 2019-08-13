<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Table;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\Table
 */
final class TableTest extends TestCase
{
    private $tableClass = 'trogon\otuspdf\Table';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->tableClass,
            new Table()
        );
    }
}
