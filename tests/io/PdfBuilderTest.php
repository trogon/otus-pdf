<?php

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\PdfBuilder;

final class PdfBuilderTest extends TestCase
{
    private $pdfBuilderClass = 'trogon\otuspdf\io\PdfBuilder';
    private $pdfObjectClass = 'trogon\otuspdf\io\pdf\PdfObject';
    private $pdfDictionaryClass = 'trogon\otuspdf\io\pdf\PdfDictionary';
    private $pdfObjectFactoryClass = 'trogon\otuspdf\io\pdf\PdfObjectFactory';
    private $pdfCrossReferenceClass = 'trogon\otuspdf\io\pdf\PdfCrossReference';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreated()
    {
        $this->assertInstanceOf(
            $this->pdfBuilderClass,
            new PdfBuilder()
        );
    }

    public function testGetObjectFactory()
    {
        $builder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->pdfObjectFactoryClass,
            $builder->objectFactory
        );
    }

    public function testCreateCrossReference()
    {
        $builder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->pdfCrossReferenceClass,
            $builder->createCrossReference()
        );
    }

    public function testCreateCrossReferenceIsEmpty()
    {
        $builder = new PdfBuilder();

        $this->assertEmpty(
            $builder->createCrossReference()->size
        );
    }

    public function testCreateDocumentInfoWitoutConfig() {
        $builder = new PdfBuilder();

        $this->assertEquals(
            null,
            $builder->createDocumentInfo()
        );
    }

    public function testCreateDocumentInfoWithConfig() {
        $builder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->pdfObjectClass,
            $builder->createDocumentInfo([
                'Author' => 'John Doe'
            ])
        );
    }

    public function testCreateDocumentInfoWithConfigHasDictionary() {
        $builder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->pdfDictionaryClass,
            $builder->createDocumentInfo([
                'Author' => 'John Doe'
            ])->content
        );
    }

    public function testCreateDocumentInfoWithConfigHasItems() {
        $builder = new PdfBuilder();

        $this->assertEquals(
            1,
            count($builder->createDocumentInfo([
                'Author' => 'John Doe'
            ])->content->items)
        );
    }

    public function testCreateFontsResource()
    {
        $builder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->pdfDictionaryClass,
            $builder->createFontsResource()
        );
    }

    public function testCreateFontsResourceIsEmpty()
    {
        $builder = new PdfBuilder();

        $this->assertEmpty(
            $builder->createFontsResource()->items
        );
    }

    public function testCreateResourceCatalog()
    {
        $builder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->pdfDictionaryClass,
            $builder->createResourceCatalog()
        );
    }

    public function testCreateResourceCatalogIsEmpty()
    {
        $builder = new PdfBuilder();

        $this->assertEmpty(
            $builder->createResourceCatalog()->items
        );
    }
}
