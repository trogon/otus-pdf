<?php
namespace trogon\otuspdf\test\unit\meta;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\meta\DocumentInfo;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\meta\DocumentInfo
 */
final class DocumentInfoTest extends TestCase
{
    private $documentInfoClass = 'trogon\otuspdf\meta\DocumentInfo';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';
    private $pdfDateFormat = "/D:([0-9]{4})([0-1][0-9])([0-2][0-9]|3[0-1])([0-2][0-9])([0-5][0-9])([0-5][0-9])([\+\-]\d{2}'\d{2})'/";

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->documentInfoClass,
            new DocumentInfo()
        );
    }

    public function testCanBeCreatedFromAllConfig()
    {
        $this->assertInstanceOf(
            $this->documentInfoClass,
            new DocumentInfo([
                'title' => 'Test title',
                'author' => 'Test author',
                'subject' => 'Test subject',
                'keywords' => 'Test, test keyword'
            ])
        );
    }

    /**
     * @expectedException trogon\otuspdf\base\InvalidCallException
     */
    public function testCannotBeCreatedFromCreationDate()
    {
        new DocumentInfo(['creationDate' => '2018-02-01']);
    }

    /**
     * @expectedException trogon\otuspdf\base\InvalidCallException
     */
    public function testCannotBeCreatedFromModificationDate()
    {
        new DocumentInfo(['modificationDate' => '2018-02-01']);
    }

    public function testReturnsOriginalDocumentCreator()
    {
        $info = new DocumentInfo();

        $this->assertEquals(
            'Otus PDF by Trogon Software',
            $info->creator
        );
    }

    public function testReturnsOriginalDocumentProducer()
    {
        $info = new DocumentInfo();

        $this->assertEquals(
            'Otus PDF by Trogon Software',
            $info->producer
        );
    }

    public function testCreationDateIsValidPdfDate()
    {
        $info = new DocumentInfo();

        $this->assertRegExp(
            $this->pdfDateFormat,
            $info->creationDate
        );
    }

    public function testModificationDateIsValidPdfDate()
    {
        $info = new DocumentInfo();

        $this->assertRegExp(
            $this->pdfDateFormat,
            $info->modificationDate
        );
    }
}
