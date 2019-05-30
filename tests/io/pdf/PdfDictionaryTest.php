<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\io\pdf\PdfDictionary;
use insma\otuspdf\base\InvalidCallException;

final class PdfDictionaryTest extends TestCase
{
    private $pdfDictionaryClass = 'insma\otuspdf\io\pdf\PdfDictionary';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pdfDictionaryClass,
            new PdfDictionary()
        );
    }

    public function testToStringReturnsValidPdfDictionaryStringForNoItems()
    {
        $pdfDictionary = new PdfDictionary();

        $this->assertEquals(
            '<< >>',
            $pdfDictionary->toString()
        );
    }
}
