<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\io\pdf\PdfName;
use insma\otuspdf\base\InvalidCallException;

final class PdfNameTest extends TestCase
{
    private $pdfNameClass = 'insma\otuspdf\io\pdf\PdfName';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pdfNameClass,
            new PdfName()
        );
    }

    public function testCanBeCreatedFromAllConfig()
    {
        $this->assertInstanceOf(
            $this->pdfNameClass,
            new PdfName([
                'value' => 'testname'
            ])
        );
    }

    public function testToStringReturnsValidPdfNameStringForDefinedName()
    {
        $pdfName = new PdfName([
            'value' => 'testname'
        ]);

        $this->assertEquals(
            '/testname',
            $pdfName->toString()
        );
    }
}
