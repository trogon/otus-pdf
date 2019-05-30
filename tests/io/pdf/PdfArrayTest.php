<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\io\pdf\PdfArray;
use insma\otuspdf\base\InvalidCallException;

final class PdfArrayTest extends TestCase
{
    private $pdfArrayClass = 'insma\otuspdf\io\pdf\PdfArray';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

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
