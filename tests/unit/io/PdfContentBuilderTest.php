<?php
namespace trogon\otuspdf\test\unit\io;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\PdfContentBuilder;

/**
 * @covers \trogon\otuspdf\io\PdfContentBuilder
 */
final class PdfContentBuilderTest extends TestCase
{
    private $pdfContentBuilderClass = 'trogon\otuspdf\io\PdfContentBuilder';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreated()
    {
        $this->assertInstanceOf(
            $this->pdfContentBuilderClass,
            new PdfContentBuilder()
        );
    }
}
