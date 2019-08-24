<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\FontReader;

/**
 * @covers \trogon\otuspdf\io\FontReader
 */
final class FontReaderTest extends TestCase
{
    private $fontReaderClass = 'trogon\otuspdf\io\FontReader';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->fontReaderClass,
            new FontReader()
        );
    }
}
