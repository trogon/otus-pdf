<?php
namespace trogon\otuspdf\test;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Text;
use trogon\otuspdf\base\InvalidCallException;

final class TextTest extends TestCase
{
    private $textClass = 'trogon\otuspdf\Text';
    private $textInfoClass = 'trogon\otuspdf\meta\TextInfo';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->textClass,
            new Text('example text')
        );
    }

    public function testReturnsInfoWhenNotConfigured()
    {
        $this->assertInstanceOf(
            $this->textInfoClass,
            (new Text('example text'))->info
        );
    }

    public function testReturnAddedTextElement()
    {
        $text = new Text('example text');

        $this->assertInstanceOf(
            $this->textClass,
            $text
        );
    }

    public function testAddTextStoreDefinedText()
    {
        $textValue = 'Example text to add';
        $text = new Text($textValue);

        $this->assertEquals(
            $textValue,
            $text->text
        );
    }
}
