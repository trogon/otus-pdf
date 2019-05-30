<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\meta\DocumentInfo;
use insma\otuspdf\base\InvalidCallException;

final class DocumentInfoTest extends TestCase
{
    private $documentInfoClass = 'insma\otuspdf\meta\DocumentInfo';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';
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
     * @expectedException insma\otuspdf\base\InvalidCallException
     */
    public function testCannotBeCreatedFromCreationDate()
    {
        new DocumentInfo(['creationDate' => '2018-02-01']);
    }

    /**
     * @expectedException insma\otuspdf\base\InvalidCallException
     */
    public function testCannotBeCreatedFromModificationDate()
    {
        new DocumentInfo(['modificationDate' => '2018-02-01']);
    }

    public function testReturnsOriginalDocumentCreator()
    {
        $info = new DocumentInfo();

        $this->assertEquals(
            'Otus PDF by Insma',
            $info->creator
        );
    }

    public function testReturnsOriginalDocumentProducer()
    {
        $info = new DocumentInfo();

        $this->assertEquals(
            'Otus PDF by Insma',
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
