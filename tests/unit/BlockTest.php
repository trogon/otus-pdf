<?php
namespace trogon\otuspdf\test;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Block;
use trogon\otuspdf\base\InvalidOperationException;

use trogon\otuspdf\test\fixture\BlockDummy;

/**
 * @covers \trogon\otuspdf\Block
 */
final class BlockTest extends TestCase
{
    private $blockClass = 'trogon\otuspdf\Block';
    private $blockInfoClass = 'trogon\otuspdf\meta\BlockInfo';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->blockClass,
            new BlockDummy()
        );
    }

    public function testReturnsInfoWhenNotConfigured()
    {
        $this->assertInstanceOf(
            $this->blockInfoClass,
            (new BlockDummy())->info
        );
    }
}
