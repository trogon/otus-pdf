<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Page;
use trogon\otuspdf\base\InvalidOperationException;

/**
 * @covers \trogon\otuspdf\Page
 */
final class PageTest extends TestCase
{
    private $pageClass = 'trogon\otuspdf\Page';
    private $pageInfoClass = 'trogon\otuspdf\meta\PageInfo';
    private $pagebreakClass = 'trogon\otuspdf\PageBreak';
    private $paragraphClass = 'trogon\otuspdf\Paragraph';
    private $textBlockClass = 'trogon\otuspdf\TextBlock';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pageClass,
            new Page()
        );
    }

    public function testCanBeCreatedFromAllConfig()
    {
        $this->assertInstanceOf(
            $this->pageClass,
            new Page([
                'orientation' => 'Landscape',
                'size' => 'A4'
            ])
        );
    }

    public function testReturnsInfoWhenNotConfigured()
    {
        $this->assertInstanceOf(
            $this->pageInfoClass,
            (new Page())->info
        );
    }

    public function testReturnsInfoWhenConfigured()
    {
        $this->assertInstanceOf(
            $this->pageInfoClass,
            (new Page([
                'orientation' => 'Landscape',
                'size' => 'A4'
            ]))->info
        );
    }

    public function testGetsBlocksWhenEmpty()
    {
        $page = new Page();
        $expectedItems = [];

        $this->assertEquals(
            $expectedItems,
            iterator_to_array($page->blocks)
        );
    }

    public function testReturnAddedTextElement()
    {
        $page = new Page();

        $this->assertInstanceOf(
            $this->textBlockClass,
            $page->addText('Example text to add')
        );
    }

    public function testAddTextStoreDefinedText()
    {
        $page = new Page();
        $textValue = 'Example text to add';
        $text = $page->addText($textValue);

        $this->assertEquals(
            $textValue,
            $text->inlines[0]->text
        );
    }

    public function testGetsBlocksWithAddedTextElement()
    {
        $page = new Page();
        $expectedItems = [];
        $expectedItems[] = $page->addText('Example text to add');

        $this->assertEquals(
            $expectedItems,
            iterator_to_array($page->blocks)
        );
    }

    public function testReturnAddedPagebreak()
    {
        $page = new Page();

        $this->assertInstanceOf(
            $this->pagebreakClass,
            $page->addPagebreak()
        );
    }

    public function testGetsBlocksWithAddedPagebreak()
    {
        $page = new Page();
        $expectedItems = [];
        $expectedItems[] = $page->addPagebreak();

        $this->assertEquals(
            $expectedItems,
            iterator_to_array($page->blocks)
        );
    }

    public function testReturnAddedParagraph()
    {
        $page = new Page();

        $this->assertInstanceOf(
            $this->paragraphClass,
            $page->addParagraph()
        );
    }

    public function testGetsBlocksWithAddedParagraph()
    {
        $page = new Page();
        $expectedItems = [];
        $expectedItems[] = $page->addParagraph();

        $this->assertEquals(
            $expectedItems,
            iterator_to_array($page->blocks)
        );
    }
}
