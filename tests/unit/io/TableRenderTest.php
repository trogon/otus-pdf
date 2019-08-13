<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\FontRender;
use trogon\otuspdf\io\PdfBuilder;
use trogon\otuspdf\io\PdfContentBuilder;
use trogon\otuspdf\io\TableRender;
use trogon\otuspdf\meta\RectInfo;

/**
 * @covers \trogon\otuspdf\io\TableRender
 */
final class TableRenderTest extends TestCase
{
    private $tableRenderClass = 'trogon\otuspdf\io\TableRender';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $pdfBuilder = new PdfBuilder();
        $fontRender = new FontRender($pdfBuilder);
        $pdfContentBuilder = new PdfContentBuilder();
        $pageBox = new RectInfo(0, 0, 100, 100);

        $this->assertInstanceOf(
            $this->tableRenderClass,
            new TableRender($pdfContentBuilder, $fontRender, $pageBox)
        );
    }
}
