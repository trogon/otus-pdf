<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\TextElement;
use trogon\otuspdf\base\InvalidCallException;

use trogon\otuspdf\test\fixture\TextElementDummy;

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
            new TextElementDummy()
        );
    }
}
