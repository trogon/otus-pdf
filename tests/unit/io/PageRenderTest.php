<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\PageRender;
use trogon\otuspdf\io\PdfBuilder;

/**
 * @covers \trogon\otuspdf\io\PageRender
 */
final class PageRenderTest extends TestCase
{
    private $pageRenderClass = 'trogon\otuspdf\io\PageRender';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreated()
    {
        $pdfBuilder = new PdfBuilder();
        $resourceCatalog = $pdfBuilder->createResourceCatalog();

        $this->assertInstanceOf(
            $this->pageRenderClass,
            new PageRender($pdfBuilder, $resourceCatalog)
        );
    }
}
