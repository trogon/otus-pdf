<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidOperationException;
use trogon\otuspdf\io\BlockRender;
use trogon\otuspdf\io\FontRender;
use trogon\otuspdf\io\PdfBuilder;

/**
 * @covers \trogon\otuspdf\io\BlockRender
 */
final class BlockRenderTest extends TestCase
{
    private $blockRenderClass = 'trogon\otuspdf\io\BlockRender';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

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
