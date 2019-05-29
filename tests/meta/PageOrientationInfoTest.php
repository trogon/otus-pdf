<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\meta\PageOrientationInfo;
use insma\otuspdf\base\InvalidCallException;

final class PageOrientationInfoTest extends TestCase
{
    private $pageOrientationInfoClass = 'insma\otuspdf\meta\PageOrientationInfo';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

    /**
     * @expectedException Error
     */
    public function testCanNotBeCreated()
    {
        new PageOrientationInfo();
    }

    public function testTwoPortraitOrientationsAreEquals()
    {
        $this->assertEquals(
            PageOrientationInfo::getPortrait(),
            PageOrientationInfo::getPortrait()
        );
    }

    public function testTwoLandscapeOrientationsAreEquals()
    {
        $this->assertEquals(
            PageOrientationInfo::getLandscape(),
            PageOrientationInfo::getLandscape()
        );
    }

    public function testPortraitIsNotEqualToLandscape()
    {
        $this->assertNotEquals(
            PageOrientationInfo::getPortrait(),
            PageOrientationInfo::getLandscape()
        );
    }

    public function testPortraitIsPortrait()
    {
        $this->assertTrue(
            PageOrientationInfo::getPortrait()->isPortrait()
        );
    }

    public function testPortraitIsNotLandscape()
    {
        $this->assertFalse(
            PageOrientationInfo::getPortrait()->isLandscape()
        );
    }

    public function testLandscapeIsLandscape()
    {
        $this->assertTrue(
            PageOrientationInfo::getLandscape()->isLandscape()
        );
    }

    public function testLandscapeIsNotPortrait()
    {
        $this->assertFalse(
            PageOrientationInfo::getLandscape()->isPortrait()
        );
    }
}
