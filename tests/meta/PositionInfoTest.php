<?php
namespace trogon\otuspdf\test\meta;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\meta\PositionInfo;
use trogon\otuspdf\base\InvalidCallException;

final class PositionInfoTest extends TestCase
{
    private $positionInfoClass = 'trogon\otuspdf\meta\PositionInfo';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreated()
    {
        $this->assertInstanceOf(
            $this->positionInfoClass,
            new PositionInfo(2.16, 7.94)
        );
    }

    public function testReturnsX()
    {
        $positionInfo = new PositionInfo(2.16, 7.94);
        $this->assertEquals(
            2.16,
            $positionInfo->x
        );
    }

    public function testReturnsY()
    {
        $positionInfo = new PositionInfo(2.16, 7.94);
        $this->assertEquals(
            7.94,
            $positionInfo->y
        );
    }
}
