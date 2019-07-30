<?php

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Document;
use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\TextRender;

/**
 * @covers \trogon\otuspdf\io\TextRender
 */
final class TextRenderTest extends TestCase
{
    private $textRenderClass = 'trogon\otuspdf\io\TextRender';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->textRenderClass,
            new TextRender()
        );
    }

    public function testGetsLineNumberAfterInit()
    {
        $textRender = new TextRender();

        $this->assertEquals(
            0,
            $textRender->lineNumber
        );
    }

    public function testGetsLineWidthAfterInit()
    {
        $textRender = new TextRender();

        $this->assertEquals(
            0,
            $textRender->lineWidth
        );
    }

    public function testSetMaxWidthStoresDoubleValue()
    {
        $textRender = new TextRender();
        $maxWidth = 120.5;

        $textRender->maxWidth = $maxWidth;

        $this->assertSame(
            $maxWidth,
            $textRender->maxWidth
        );
    }
}
