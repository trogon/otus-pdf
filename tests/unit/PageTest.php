<?php
namespace trogon\otuspdf\test;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Page;
use trogon\otuspdf\base\InvalidCallException;

final class PageTest extends TestCase
{
    private $pageClass = 'trogon\otuspdf\Page';
    private $pageInfoClass = 'trogon\otuspdf\meta\PageInfo';
    private $textBlockClass = 'trogon\otuspdf\TextBlock';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

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

    public function testGetItemsOnEmpty()
    {
        $page = new Page();
        $expectedItems = [];
        $expectedItems[] = $page->addText('Example text to add');

        $this->assertEquals(
            $expectedItems,
            iterator_to_array($page->blocks)
        );
    }
}
