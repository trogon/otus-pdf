<?php

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\meta\PageOrientationInfo;
use trogon\otuspdf\base\InvalidCallException;

final class PageOrientationInfoTest extends TestCase
{
    private $pageOrientationInfoClass = 'trogon\otuspdf\meta\PageOrientationInfo';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    /**
     * @expectedException Error
     */
    public function testCanNotBeCreated()
    {
        if (!version_compare(PHP_VERSION, '7.1.0', '>=')) {
            $this->markTestSkipped(
              'PHP treats it as PHP Fatal error. Can not be tested until PHP 7.1.'
            );
        }
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
