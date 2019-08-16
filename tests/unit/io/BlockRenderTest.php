<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\BlockRender;
use trogon\otuspdf\io\FontRender;
use trogon\otuspdf\io\PdfBuilder;

/**
 * @covers \trogon\otuspdf\io\BlockRender
 */
final class BlockRenderTest extends TestCase
{
    private $blockRenderClass = 'trogon\otuspdf\io\BlockRender';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreated()
    {
        $pdfBuilder = new PdfBuilder();
        $fontRender = new FontRender($pdfBuilder);

        $this->assertInstanceOf(
            $this->blockRenderClass,
            new BlockRender($fontRender)
        );
    }
}
