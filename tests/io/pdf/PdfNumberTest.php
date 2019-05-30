<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\io\pdf\PdfNumber;
use insma\otuspdf\base\InvalidCallException;

final class PdfNumberTest extends TestCase
{
    private $pdfNumberClass = 'insma\otuspdf\io\pdf\PdfNumber';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

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
