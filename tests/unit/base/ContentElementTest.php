<?php

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\base\ContentElement;
use trogon\otuspdf\base\InvalidCallException;

final class InvalidContentElementDummy extends ContentElement
{

}

final class ContentElementDummy extends ContentElement
{
    protected function createInfo($config)
    {
        return $config;
    }
}

/**
 * @covers \trogon\otuspdf\base\ContentElement
 */
final class ContentElementTest extends TestCase
{
    private $contentElementClass = 'trogon\otuspdf\base\ContentElement';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->contentElementClass,
            new ContentElementDummy()
        );
    }

    /**
     * @expectedException trogon\otuspdf\base\NotImplementedException
     */
    public function testCanNotCreateWhenCreateInfoNotOverridden()
    {
        new InvalidContentElementDummy();
    }

    public function testInfoReturnsCorrectTypeOnNoConfig()
    {
        $dummy = new ContentElementDummy();

        $this->assertEquals(
            'array',
            gettype($dummy->info)
        );
    }

    public function testInfoReturnsCorrectType()
    {
        $dummy = new ContentElementDummy([
            'example' => 'Dummy value'
        ]);

        $this->assertEquals(
            'array',
            gettype($dummy->info)
        );
    }

    public function testInfoAssignedFromConfig()
    {
        $config = [
            'example' => 'Dummy value'
        ];

        $dummy = new ContentElementDummy($config);

        $this->assertEquals(
            $config,
            $dummy->info
        );
    }
}