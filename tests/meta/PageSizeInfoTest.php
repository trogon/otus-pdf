<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\meta\PageSizeInfo;
use insma\otuspdf\base\InvalidCallException;

final class PageSizeInfoTest extends TestCase
{
    private $pageSizeInfoClass = 'insma\otuspdf\meta\PageSizeInfo';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromWidthAndHeight()
    {
        $this->assertInstanceOf(
            $this->pageSizeInfoClass,
            new PageSizeInfo(14, 10)
        );
    }

    /**
     * @expectedException insma\otuspdf\base\InvalidCallException
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
