<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\Text;
use insma\otuspdf\base\InvalidCallException;

final class TextTest extends TestCase
{
    private $textClass = 'insma\otuspdf\Text';
    private $textInfoClass = 'insma\otuspdf\meta\TextInfo';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

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
