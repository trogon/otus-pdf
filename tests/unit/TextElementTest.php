<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TextElement;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\TextElement
 */
final class TextElementTest extends TestCase
{
    private $textElementClass = 'trogon\otuspdf\TextElement';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->textElementClass,
            new TextElement()
        );
    }
}
