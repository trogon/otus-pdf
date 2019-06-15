<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\Page;
use insma\otuspdf\base\InvalidCallException;

final class PageTest extends TestCase
{
    private $pageClass = 'insma\otuspdf\Page';
    private $pageInfoClass = 'insma\otuspdf\meta\PageInfo';
    private $textClass = 'insma\otuspdf\Text';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

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
            $this->textClass,
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
            $text->text
        );
    }

    public function testGetItemsOnEmpty()
    {
        $page = new Page();
        $expectedItems = [];
        $expectedItems[] = $page->addText('Example text to add');

        $this->assertEquals(
            $expectedItems,
            $page->items
        );
    }
}
