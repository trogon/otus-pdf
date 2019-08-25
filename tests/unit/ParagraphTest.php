<?php
namespace trogon\otuspdf\test;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Paragraph;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\Paragraph
 */
final class ParagraphTest extends TestCase
{
    private $paragraphClass = 'trogon\otuspdf\Paragraph';
    private $paragraphInfoClass = 'trogon\otuspdf\meta\ParagraphInfo';
    private $runClass = 'trogon\otuspdf\Run';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->paragraphClass,
            new Paragraph()
        );
    }

    public function testCanBeCreatedFromAllConfig()
    {
        $this->assertInstanceOf(
            $this->paragraphClass,
            new Paragraph([
                'textIndent' => 2.1
            ])
        );
    }

    public function testReturnsInfoWhenNotConfigured()
    {
        $this->assertInstanceOf(
            $this->paragraphInfoClass,
            (new Paragraph())->info
        );
    }

    public function testReturnsInfoWhenConfigured()
    {
        $this->assertInstanceOf(
            $this->paragraphInfoClass,
            (new Paragraph([
                'textIndent' => 2.1
            ]))->info
        );
    }

    public function testGetsInlinesWhenEmpty()
    {
        $paragraph = new Paragraph();
        $expectedItems = [];

        $this->assertEquals(
            $expectedItems,
            iterator_to_array($paragraph->inlines)
        );
    }

    public function testReturnAddedRun()
    {
        $paragraph = new Paragraph();

        $this->assertInstanceOf(
            $this->runClass,
            $paragraph->addRun('Example text to add')
        );
    }

    public function testAddRunStoreDefinedText()
    {
        $paragraph = new Paragraph();
        $textValue = 'Example text to add';
        $run = $paragraph->addRun($textValue);

        $this->assertEquals(
            $textValue,
            $run->text
        );
    }

    public function testGetsInlinesWithAddedRun()
    {
        $paragraph = new Paragraph();
        $expectedItems = [];
        $expectedItems[] = $paragraph->addRun('Example text to add');

        $this->assertEquals(
            $expectedItems,
            iterator_to_array($paragraph->inlines)
        );
    }
}
