<?php
namespace trogon\otuspdf\test\unit\io\pdf;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\io\pdf\PdfNull;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\io\pdf\PdfNull
 */
final class PdfNullTest extends TestCase
{
    private $pdfNullClass = 'trogon\otuspdf\io\pdf\PdfNull';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pdfNullClass,
            new PdfNull()
        );
    }

    public function testToStringReturnsTextEqualsNull()
    {
        $pdfNull = new PdfNull();

        $this->assertEquals(
            'null',
            $pdfNull->toString()
        );
    }
}
