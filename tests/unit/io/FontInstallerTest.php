<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidOperationException;
use trogon\otuspdf\io\FontInstaller;

/**
 * @covers \trogon\otuspdf\io\FontInstaller
 */
final class FontInstallerTest extends TestCase
{
    private $fontInstallerClass = 'trogon\otuspdf\io\FontInstaller';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->fontInstallerClass,
            new FontInstaller()
        );
    }
}
