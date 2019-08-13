<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\FontRender;
use trogon\otuspdf\io\InlineRender;
use trogon\otuspdf\io\PdfBuilder;
use trogon\otuspdf\io\PdfContentBuilder;
use trogon\otuspdf\meta\RectInfo;

/**
 * @covers \trogon\otuspdf\io\InlineRender
 */
final class InlineRenderTest extends TestCase
{
    private $inlineRenderClass = 'trogon\otuspdf\io\InlineRender';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreated()
    {
        $pdfBuilder = new PdfBuilder();
        $fontRender = new FontRender($pdfBuilder);
        $pdfContentBuilder = new PdfContentBuilder();
        $pageBox = new RectInfo(0, 0, 100, 100);

        $this->assertInstanceOf(
            $this->inlineRenderClass,
            new InlineRender($pdfContentBuilder, $fontRender, $pageBox)
        );
    }
}
