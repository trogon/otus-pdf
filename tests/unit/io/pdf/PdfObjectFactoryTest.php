<?php
namespace trogon\otuspdf\test\unit\io\pdf;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\io\pdf\PdfObjectFactory;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\io\pdf\PdfObjectFactory
 */
final class PdfObjectFactoryTest extends TestCase
{
    private $pdfObjectClass = 'trogon\otuspdf\io\pdf\PdfObject';
    private $pdfObjectFactoryClass = 'trogon\otuspdf\io\pdf\PdfObjectFactory';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pdfObjectFactoryClass,
            new PdfObjectFactory()
        );
    }

    public function testCreateReturnsPdfObject()
    {
        $pdfObjectFactory = new PdfObjectFactory();

        $this->assertInstanceOf(
            $this->pdfObjectClass,
            $pdfObjectFactory->create()
        );
    }

    public function testCreateReturnsFirstObjectsIdEqualsZero()
    {
        $pdfObjectFactory = new PdfObjectFactory();

        $firstObject = $pdfObjectFactory->create();

        $this->assertEquals(
            0,
            $firstObject->id
        );
    }

    public function testCreateReturnsObjectsWithNextId()
    {
        $pdfObjectFactory = new PdfObjectFactory();
        $pdfObjectFactory->create();

        for ($i = 1; $i < 5; $i++) {
            $nextObject = $pdfObjectFactory->create();

            $this->assertEquals(
                $i,
                $nextObject->id
            );
        }
    }
}
