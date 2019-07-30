<?php
namespace trogon\otuspdf\test;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Inline;
use trogon\otuspdf\base\InvalidCallException;

use trogon\otuspdf\test\fixture\InlineDummy;

/**
 * @covers \trogon\otuspdf\Inline
 */
final class InlineTest extends TestCase
{
    private $inlineClass = 'trogon\otuspdf\Inline';
    private $inlineInfoClass = 'trogon\otuspdf\meta\InlineInfo';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->inlineClass,
            new InlineDummy()
        );
    }

    public function testReturnsInfoWhenNotConfigured()
    {
        $this->assertInstanceOf(
            $this->inlineInfoClass,
            (new InlineDummy())->info
        );
    }
}
