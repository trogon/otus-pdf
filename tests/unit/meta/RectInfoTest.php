<?php
namespace trogon\otuspdf\test\meta;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\meta\RectInfo;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\meta\RectInfo
 */
final class RectInfoTest extends TestCase
{
    private $rectInfoClass = 'trogon\otuspdf\meta\RectInfo';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreated()
    {
        $this->assertInstanceOf(
            $this->rectInfoClass,
            new RectInfo(2.16, 7.94, 14.53, 11.37)
        );
    }

    public function testReturnsX()
    {
        $rectInfo = new RectInfo(2.16, 7.94, 14.53, 11.37);
        $this->assertEquals(
            2.16,
            $rectInfo->x
        );
    }

    public function testReturnsY()
    {
        $rectInfo = new RectInfo(2.16, 7.94, 14.53, 11.37);
        $this->assertEquals(
            7.94,
            $rectInfo->y
        );
    }

    public function testReturnsWidth()
    {
        $rectInfo = new RectInfo(2.16, 7.94, 14.53, 11.37);
        $this->assertEquals(
            14.53,
            $rectInfo->width
        );
    }

    public function testReturnsHeight()
    {
        $rectInfo = new RectInfo(2.16, 7.94, 14.53, 11.37);
        $this->assertEquals(
            11.37,
            $rectInfo->height
        );
    }
}
