<?php
namespace trogon\otuspdf\test\unit\io\pdf;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\io\pdf\PdfNull;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\io\pdf\PdfNull
 */
final class PdfNullTest extends TestCase
{
    private $pdfNullClass = 'trogon\otuspdf\io\pdf\PdfNull';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

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
