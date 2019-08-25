<?php
namespace trogon\otuspdf\test\unit\io\pdf;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\io\pdf\PdfBoolean;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\io\pdf\PdfBoolean
 */
final class PdfBooleanTest extends TestCase
{
    private $pdfBooleanClass = 'trogon\otuspdf\io\pdf\PdfBoolean';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testToStringReturnsTextEqualsFalseWhenNotDefined()
    {
        $pdfBoolean = new PdfBoolean();

        $this->assertEquals(
            'false',
            $pdfBoolean->toString()
        );
    }

    public function testToStringReturnsTextEqualsTrueWhenPositive()
    {
        $pdfBoolean = new PdfBoolean([
            'value' => true
        ]);

        $this->assertEquals(
            'true',
            $pdfBoolean->toString()
        );
    }

    public function testToStringReturnsTextEqualsFalseWhenNegative()
    {
        $pdfBoolean = new PdfBoolean([
            'value' => false
        ]);

        $this->assertEquals(
            'false',
            $pdfBoolean->toString()
        );
    }


    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pdfBooleanClass,
            new PdfBoolean()
        );
    }

    public function testCanBeCreatedFromAllConfig()
    {
        $this->assertInstanceOf(
            $this->pdfBooleanClass,
            new PdfBoolean(['value' => true])
        );
    }

    public function testGetValueInitializedAsZero()
    {
        $pdfBoolean = new PdfBoolean();
    
        $this->assertEquals(
            false,
            $pdfBoolean->value
        );
    }

    public function testSetValueStoresTrue()
    {
        $pdfBoolean = new PdfBoolean();
        $expectedValue = true;
        $pdfBoolean->value = $expectedValue;
    
        $this->assertEquals(
            $expectedValue,
            $pdfBoolean->value
        );
    }

    public function testToStringEqualsTextFalseWhenEmpty()
    {   $pdfBoolean = new PdfBoolean();
        $expectedValue = "false";
    
        $this->assertEquals(
            $expectedValue,
            $pdfBoolean->toString()
        );
    }

    public function testToStringEqualsTextFalseWhenValueIsFalse()
    {   $pdfBoolean = new PdfBoolean();
        $pdfBoolean->value = false;
        $expectedValue = "false";
    
        $this->assertEquals(
            $expectedValue,
            $pdfBoolean->toString()
        );
    }

    public function testToStringEqualsTextTrueWhenValueIsTrue()
    {   $pdfBoolean = new PdfBoolean();
        $pdfBoolean->value = true;
        $expectedValue = "true";
    
        $this->assertEquals(
            $expectedValue,
            $pdfBoolean->toString()
        );
    }
}
