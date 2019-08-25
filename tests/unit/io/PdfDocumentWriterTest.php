<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Document;
use trogon\otuspdf\base\InvalidOperationException;
use trogon\otuspdf\io\PdfDocumentWriter;

/**
 * @covers \trogon\otuspdf\io\PdfDocumentWriter
 */
final class PdfDocumentWriterTest extends TestCase
{
    private $pdfDocumentWriterClass = 'trogon\otuspdf\io\PdfDocumentWriter';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreated()
    {
        $document = new Document();

        $this->assertInstanceOf(
            $this->pdfDocumentWriterClass,
            new PdfDocumentWriter($document)
        );
    }
}
