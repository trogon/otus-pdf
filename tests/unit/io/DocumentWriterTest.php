<?php
namespace trogon\otuspdf\test\unit\io;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Document;
use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\DocumentWriter;

use trogon\otuspdf\test\fixture\ProviderStub;

/**
 * @covers \trogon\otuspdf\io\DocumentWriter
 */
final class DocumentWriterTest extends TestCase
{
    private $documentWriterClass = 'trogon\otuspdf\io\DocumentWriter';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function setUp()
    {
        DocumentWriter::$providers['ico'] = '\trogon\otuspdf\test\fixture\ProviderNotInstalled';
        DocumentWriter::$providers['tar'] = '\trogon\otuspdf\test\fixture\ProviderStub';
        ProviderStub::$document = null;
    }

    /**
     * @requires PHP >= 7.1
     * @expectedException ArgumentCountError
     */
    public function testCannotBeCreatedFromEmptyConfig()
    {
        if (!version_compare(PHP_VERSION, '7.1.0', '>=')) {
            $this->markTestSkipped(
              'PHP treats it as PHP Fatal error. Can not be tested until PHP 7.1.'
            );
        }

        $this->assertInstanceOf(
            $this->documentWriterClass,
            new DocumentWriter()
        );
    }

    public function testCanBeCreatedFromDocument()
    {
        $this->assertInstanceOf(
            $this->documentWriterClass,
            new DocumentWriter(new Document())
        );
    }

    public function testCanBeSavedWhenProviderInstalled()
    {
        $document = new Document();
        $writer = new DocumentWriter($document);

        $this->assertEquals(
            true,
            $writer->save('example.tar')
        );
        $this->assertSame(
            $document,
            ProviderStub::$document
        );
    }

    /**
     * @expectedException trogon\otuspdf\io\UnknownFormatException
     */
    public function testCannotBeSavedWhenProviderNotInstalled()
    {
        $writer = new DocumentWriter(new Document());
        $writer->save('example.ico');
    }

    /**
     * @expectedException trogon\otuspdf\io\UnknownFormatException
     */
    public function testCannotBeSavedForUnsupportedFormat()
    {
        $writer = new DocumentWriter(new Document());
        $writer->save('example.zip');
    }

    public function testCanBeStringifyWhenProviderInstalled()
    {
        $document = new Document();
        $writer = new DocumentWriter($document);

        $this->assertEquals(
            'Simple provider text',
            $writer->toString('tar')
        );
        $this->assertSame(
            $document,
            ProviderStub::$document
        );
    }

    /**
     * @expectedException trogon\otuspdf\io\UnknownFormatException
     */
    public function testCannotBeStringifyWhenProviderNotInstalled()
    {
        $writer = new DocumentWriter(new Document());
        $writer->toString('ico');
    }

    /**
     * @expectedException trogon\otuspdf\io\UnknownFormatException
     */
    public function testCannotBeStringifyForUnsupportedFormat()
    {
        $writer = new DocumentWriter(new Document());
        $writer->toString('zip');
    }
}
