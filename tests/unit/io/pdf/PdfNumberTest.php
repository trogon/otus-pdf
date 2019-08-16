<?php
namespace trogon\otuspdf\test\unit\io\pdf;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\io\pdf\PdfNumber;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\io\pdf\PdfNumber
 */
final class PdfNumberTest extends TestCase
{
    private $pdfNumberClass = 'trogon\otuspdf\io\pdf\PdfNumber';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pdfNumberClass,
            new PdfNumber()
        );
    }

    public function testCanBeCreatedFromAllConfig()
    {
        $this->assertInstanceOf(
            $this->pdfNumberClass,
            new PdfNumber([
                'value' => 32.57
            ])
        );
    }

    public function testToStringReturnsValidPdfNumberStringForDefinedValue()
    {
        $pdfNumber = new PdfNumber([
            'value' => 32.57
        ]);

        $this->assertSame(
            '32.57',
            $pdfNumber->toString()
        );
    }
}
