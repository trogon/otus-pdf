<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\FontInstaller;

/**
 * @covers \trogon\otuspdf\io\FontInstaller
 */
final class FontInstallerTest extends TestCase
{
    private $fontInstallerClass = 'trogon\otuspdf\io\FontInstaller';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->fontInstallerClass,
            new FontInstaller()
        );
    }
}
