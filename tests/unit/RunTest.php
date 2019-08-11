<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Run;
use trogon\otuspdf\base\InvalidCallException;

/**
 * @covers \trogon\otuspdf\Run
 */
final class RunTest extends TestCase
{
    private $runClass = 'trogon\otuspdf\Run';
    private $inlineInfoClass = 'trogon\otuspdf\meta\InlineInfo';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->runClass,
            new Run('example text')
        );
    }

    public function testReturnsInfoWhenNotConfigured()
    {
        $this->assertInstanceOf(
            $this->inlineInfoClass,
            (new Run('example text'))->info
        );
    }

    public function testReturnAddedRunElement()
    {
        $run = new Run('example text');

        $this->assertInstanceOf(
            $this->runClass,
            $run
        );
    }

    public function testAddRunStoreDefinedText()
    {
        $text = 'Example text to add';
        $run = new Run($text);

        $this->assertEquals(
            $text,
            $run->text
        );
    }
}
