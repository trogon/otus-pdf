<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\FontRender;
use trogon\otuspdf\io\PdfBuilder;

/**
 * @covers \trogon\otuspdf\io\FontRender
 */
final class FontRenderTest extends TestCase
{
    private $fontRenderClass = 'trogon\otuspdf\io\FontRender';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreated()
    {
        $pdfBuilder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->fontRenderClass,
            new FontRender($pdfBuilder)
        );
    }
}
