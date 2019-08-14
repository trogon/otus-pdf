<?php
namespace trogon\otuspdf\test\unit\io;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\PdfBuilder;
use trogon\otuspdf\io\pdf\PdfArray;
use trogon\otuspdf\io\pdf\PdfDictionary;
use trogon\otuspdf\io\pdf\PdfName;
use trogon\otuspdf\io\pdf\PdfObject;
use trogon\otuspdf\io\pdf\PdfObjectReference;
use trogon\otuspdf\io\pdf\PdfStream;
use trogon\otuspdf\io\pdf\PdfString;

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

    public function testRegisterCropBoxAddsNewEntry()
    {
        $builder = new PdfBuilder();
        $page = new PdfObject(371);
        $pageContent = $page->content = new PdfDictionary();
        $cropBox = new PdfObject(102);

        $builder->registerCropBox($page, $cropBox);

        $this->assertArrayHasKey(
            'CropBox',
            $pageContent->getItems()
        );
    }

    public function testRegisterCropBoxAddsObjectRef()
    {
        $builder = new PdfBuilder();
        $page = new PdfObject(371);
        $pageContent = $page->content = new PdfDictionary();
        $cropBox = new PdfObject(102);
        $cropBoxRef = new PdfObjectReference(['object' => $cropBox]);

        $builder->registerCropBox($page, $cropBox);

        $this->assertEquals(
            $cropBoxRef,
            $pageContent->getItem('CropBox')
        );
    }

    public function testRegisterExternalGraphicStateAddsNewEntry()
    {
        $builder = new PdfBuilder();
        $extGStates = new PdfDictionary();
        $extGState = new PdfObject(102);
        $alias = 'gst-alias1';

        $builder->registerExternalGraphicState($extGStates, $extGState, $alias);

        $this->assertArrayHasKey(
            $alias,
            $extGStates->getItems()
        );
    }

    public function testRegisterExternalGraphicStateAddsObjectRef()
    {
        $builder = new PdfBuilder();
        $extGStates = new PdfDictionary();
        $extGState = new PdfObject(102);
        $extGStateRef = new PdfObjectReference(['object' => $extGState]);
        $alias = 'gst-alias1';

        $builder->registerExternalGraphicState($extGStates, $extGState, $alias);

        $this->assertEquals(
            $extGStateRef,
            $extGStates->getItem($alias)
        );
    }

    public function testRegisterExternalGraphicStatesResourceAddsNewEntry()
    {
        $builder = new PdfBuilder();
        $resources = new PdfDictionary();
        $extGStates = new PdfObject(572);

        $builder->registerExternalGraphicStatesResource($resources, $extGStates);

        $this->assertArrayHasKey(
            'ExtGState',
            $resources->getItems()
        );
    }

    public function testRegisterExternalGraphicStatesResourceAddsObjectRef()
    {
        $builder = new PdfBuilder();
        $resources = new PdfDictionary();
        $extGStates = new PdfObject(572);
        $cropBoxRef = new PdfObjectReference(['object' => $extGStates]);

        $builder->registerExternalGraphicStatesResource($resources, $extGStates);

        $this->assertEquals(
            $cropBoxRef,
            $resources->getItem('ExtGState')
        );
    }

    public function testRegisterMediaBoxAddsNewEntry()
    {
        $builder = new PdfBuilder();
        $page = new PdfObject(371);
        $pageContent = $page->content = new PdfDictionary();
        $mediaBox = new PdfObject(102);

        $builder->registerMediaBox($page, $mediaBox);

        $this->assertArrayHasKey(
            'MediaBox',
            $pageContent->getItems()
        );
    }

    public function testRegisterMediaBoxAddsObjectRef()
    {
        $builder = new PdfBuilder();
        $page = new PdfObject(371);
        $pageContent = $page->content = new PdfDictionary();
        $mediaBox = new PdfObject(102);
        $mediaBoxRef = new PdfObjectReference(['object' => $mediaBox]);

        $builder->registerMediaBox($page, $mediaBox);

        $this->assertEquals(
            $mediaBoxRef,
            $pageContent->getItem('MediaBox')
        );
    }

    public function testRegisterMetadataAddsNewEntry()
    {
        $builder = new PdfBuilder();
        $page = new PdfObject(371);
        $pageContent = $page->content = new PdfDictionary();
        $metadata = new PdfObject(625);

        $builder->registerMetadata($page, $metadata);

        $this->assertArrayHasKey(
            'Metadata',
            $pageContent->getItems()
        );
    }

    public function testRegisterMetadataAddsObjectRef()
    {
        $builder = new PdfBuilder();
        $page = new PdfObject(371);
        $pageContent = $page->content = new PdfDictionary();
        $metadata = new PdfObject(625);
        $metadataRef = new PdfObjectReference(['object' => $metadata]);

        $builder->registerMetadata($page, $metadata);

        $this->assertEquals(
            $metadataRef,
            $pageContent->getItem('Metadata')
        );
    }

    public function testRegisterOutlinesAddsNewEntry()
    {
        $builder = new PdfBuilder();
        $catalog = new PdfObject(271);
        $catalogContent = $catalog->content = new PdfDictionary();
        $outlines = new PdfObject(836);

        $builder->registerOutlines($catalog, $outlines);

        $this->assertArrayHasKey(
            'Outlines',
            $catalogContent->getItems()
        );
    }

    public function testRegisterOutlinesAddsObjectRef()
    {
        $builder = new PdfBuilder();
        $catalog = new PdfObject(271);
        $catalogContent = $catalog->content = new PdfDictionary();
        $outlines = new PdfObject(836);
        $outlinesRef = new PdfObjectReference(['object' => $outlines]);

        $builder->registerOutlines($catalog, $outlines);

        $this->assertEquals(
            $outlinesRef,
            $catalogContent->getItem('Outlines')
        );
    }

    public function testRegisterPageCollectionAddsNewEntry()
    {
        $builder = new PdfBuilder();
        $catalog = new PdfObject(271);
        $catalogContent = $catalog->content = new PdfDictionary();
        $pageCollection = new PdfObject(342);

        $builder->registerPageCollection($catalog, $pageCollection);

        $this->assertArrayHasKey(
            'Pages',
            $catalogContent->getItems()
        );
    }

    public function testRegisterPageCollectionAddsObjectRef()
    {
        $builder = new PdfBuilder();
        $catalog = new PdfObject(271);
        $catalogContent = $catalog->content = new PdfDictionary();
        $pageCollection = new PdfObject(342);
        $pageCollectionRef = new PdfObjectReference(['object' => $pageCollection]);

        $builder->registerPageCollection($catalog, $pageCollection);

        $this->assertEquals(
            $pageCollectionRef,
            $catalogContent->getItem('Pages')
        );
    }

    public function testRegisterPageContentAddsNewEntry()
    {
        $builder = new PdfBuilder();
        $page = new PdfObject(371);
        $pageContent = $page->content = new PdfDictionary();
        $pageContentObj = new PdfObject(448);

        $builder->registerPageContent($page, $pageContentObj);

        $this->assertArrayHasKey(
            'Contents',
            $pageContent->getItems()
        );
    }

    public function testRegisterPageContentAddsObjectRef()
    {
        $builder = new PdfBuilder();
        $page = new PdfObject(371);
        $pageContent = $page->content = new PdfDictionary();
        $pageContentObj = new PdfObject(448);
        $pageContentObjRef = new PdfObjectReference(['object' => $pageContentObj]);

        $builder->registerPageContent($page, $pageContentObj);

        $this->assertEquals(
            $pageContentObjRef,
            $pageContent->getItem('Contents')
        );
    }

    public function testRegisterProcSetResourceAddsNewEntry()
    {
        $builder = new PdfBuilder();
        $resources = new PdfDictionary();
        $procSet = new PdfObject(926);

        $builder->registerProcSetResource($resources, $procSet);

        $this->assertArrayHasKey(
            'ProcSet',
            $resources->getItems()
        );
    }

    public function testRegisterProcSetResourceAddsObjectRef()
    {
        $builder = new PdfBuilder();
        $resources = new PdfDictionary();
        $procSet = new PdfObject(926);
        $procSetRef = new PdfObjectReference(['object' => $procSet]);

        $builder->registerProcSetResource($resources, $procSet);

        $this->assertEquals(
            $procSetRef,
            $resources->getItem('ProcSet')
        );
    }
}
