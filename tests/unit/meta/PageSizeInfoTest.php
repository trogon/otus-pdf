<?php
namespace trogon\otuspdf\test\unit\meta;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\meta\PageSizeInfo;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\meta\PageSizeInfo
 */
final class PageSizeInfoTest extends TestCase
{
    private $pageSizeInfoClass = 'trogon\otuspdf\meta\PageSizeInfo';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromWidthAndHeight()
    {
        $this->assertInstanceOf(
            $this->pageSizeInfoClass,
            new PageSizeInfo(14, 10)
        );
    }

    /**
     * @expectedException trogon\otuspdf\base\InvalidCallException
     */
    public function testCanNotBeCreatedForPortraitOrientation()
    {
        new PageSizeInfo(10, 14);
    }

    public function testReturnsDefinedWidth()
    {
        $this->assertEquals(
            16,
            (new PageSizeInfo(16, 11))->width
        );
    }

    public function testReturnsDefinedHeight()
    {
        $this->assertEquals(
            5,
            (new PageSizeInfo(7, 5))->height
        );
    }
}
