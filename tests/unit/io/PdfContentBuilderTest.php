<?php
namespace trogon\otuspdf\test\unit\io;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidOperationException;
use trogon\otuspdf\io\PdfContentBuilder;

/**
 * @covers \trogon\otuspdf\io\PdfContentBuilder
 */
final class PdfContentBuilderTest extends TestCase
{
    private $pdfContentBuilderClass = 'trogon\otuspdf\io\PdfContentBuilder';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreated()
    {
        $this->assertInstanceOf(
            $this->pdfContentBuilderClass,
            new PdfContentBuilder()
        );
    }

    public function testBeginTextOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::NONE_STATE);

        $this->assertEquals(
            'BT',
            trim($builder->beginText())
        );
    }

    public function testBeginTextRenderOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE);

        $this->assertEquals(
            '(',
            trim($builder->beginTextRender())
        );
    }

    public function testEndTextRenderOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE_SCOPE);

        $this->assertEquals(
            ') Tj',
            trim($builder->endTextRender())
        );
    }

    public function testEndTextOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE);

        $this->assertEquals(
            'ET',
            trim($builder->endText())
        );
    }

    public function testSetCharacterSpacingOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE);
        $value = 3.11;

        $this->assertEquals(
            "{$value} Tc",
            trim($builder->setCharacterSpacing($value))
        );
    }

    public function testSetHorizontalScalingOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE);
        $value = 3.12;

        $this->assertEquals(
            "{$value} Tz",
            trim($builder->setHorizontalScaling($value))
        );
    }

    public function testSetTextFontOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE);
        $key = "key-1";
        $value = 3.13;

        $this->assertEquals(
            "/{$key} {$value} Tf",
            trim($builder->setTextFont($key, $value))
        );
    }

    public function testSetTextLeadingOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE);
        $value = 3.14;

        $this->assertEquals(
            "{$value} TL",
            trim($builder->setTextLeading($value))
        );
    }

    public function testSetTextRenderingModeOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE);
        $value = 3.15;

        $this->assertEquals(
            "{$value} Tr",
            trim($builder->setTextRenderingMode($value))
        );
    }

    public function testSetTextRiseOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE);
        $value = 3.16;

        $this->assertEquals(
            "{$value} Ts",
            trim($builder->setTextRise($value))
        );
    }

    public function testSetWordSpacingOutput()
    {
        $builder = new PdfContentBuilder();
        $builder->setState(PdfContentBuilder::TEXT_STATE);
        $value = 3.17;

        $this->assertEquals(
            "{$value} Tw",
            trim($builder->setWordSpacing($value))
        );
    }
}
