<?php
namespace trogon\otuspdf\test\unit\io;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\PdfBuilder;

/**
 * @covers \trogon\otuspdf\io\PdfBuilder
 */
final class PdfBuilderTest extends TestCase
{
    private $pdfArrayClass = 'trogon\otuspdf\io\pdf\PdfArray';
    private $pdfBuilderClass = 'trogon\otuspdf\io\PdfBuilder';
    private $pdfCrossReferenceClass = 'trogon\otuspdf\io\pdf\PdfCrossReference';
    private $pdfDictionaryClass = 'trogon\otuspdf\io\pdf\PdfDictionary';
    private $pdfNumberClass = 'trogon\otuspdf\io\pdf\PdfNumber';
    private $pdfObjectClass = 'trogon\otuspdf\io\pdf\PdfObject';
    private $pdfObjectFactoryClass = 'trogon\otuspdf\io\pdf\PdfObjectFactory';
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

    public function testCreateMediaBoxReturnsPdfArray()
    {
        $builder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->pdfArrayClass,
            $builder->createMediaBox(1.43, 31, 24.1, 12)
        );
    }

    public function testCreateMediaBoxReturnsItemsOfTypePdfNumber()
    {
        $builder = new PdfBuilder();

        $this->assertContainsOnlyInstancesOf(
            $this->pdfNumberClass,
            $builder->createMediaBox(1.43, 31, 24.1, 12)->items
        );
    }

    public function testCreateMediaBoxReturnsFourValues()
    {
        $builder = new PdfBuilder();

        $pdfNumberValue = function($pdfNumber) {
            return $pdfNumber->value;
        };

        $this->assertEquals(
            [0, 0, 1.43, 31],
            array_map($pdfNumberValue, $builder->createMediaBox(1.43, 31)->items)
        );
    }

    public function testCreateMediaBoxStoresValuesInRightOrder()
    {
        $builder = new PdfBuilder();

        $pdfNumberValue = function($pdfNumber) {
            return $pdfNumber->value;
        };

        $this->assertEquals(
            [24.1, 12, 1.43, 31],
            array_map($pdfNumberValue, $builder->createMediaBox(1.43, 31, 24.1, 12)->items)
        );
    }

    public function testCreatePageContentReturnsPdfObject()
    {
        $builder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->pdfObjectClass,
            $builder->createPageContent()
        );
    }

    public function testCreatePageContentReturnsPdfDictionaryAsContent()
    {
        $builder = new PdfBuilder();

        $this->assertInstanceOf(
            $this->pdfDictionaryClass,
            $builder->createPageContent()->content
        );
    }

    public function testCreatePageContentReturnsEmptyPdfDictionaryAsContent()
    {
        $builder = new PdfBuilder();

        $this->assertEquals(
            [],
            $builder->createPageContent()->content->items
        );
    }
}
