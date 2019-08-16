<?php
namespace trogon\otuspdf\test\unit\io\pdf;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\io\pdf\PdfArray;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\io\pdf\PdfArray
 */
final class PdfArrayTest extends TestCase
{
    private $pdfArrayClass = 'trogon\otuspdf\io\pdf\PdfArray';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pdfArrayClass,
            new PdfArray()
        );
    }

    public function testToStringReturnsValidPdfArrayStringForNoItems()
    {
        $pdfArray = new PdfArray();

        $this->assertEquals(
            '[]',
            $pdfArray->toString()
        );
    }
}
