<?php
namespace trogon\otuspdf\test;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Inline;
use trogon\otuspdf\InlineCollection;
use trogon\otuspdf\base\InvalidCallException;

use trogon\otuspdf\test\fixture\InlineStub;

/**
 * @covers \trogon\otuspdf\InlineCollection
 */
final class InlineCollectionTest extends TestCase
{
    private $inlineCollectionClass = 'trogon\otuspdf\InlineCollection';
    private $inlineClass = 'trogon\otuspdf\Inline';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->inlineCollectionClass,
            new InlineCollection()
        );
    }

    public function testAddReturnsInline()
    {
        $collection = new InlineCollection();
        $this->assertInstanceOf(
            $this->inlineClass,
            $collection->add(new InlineStub())
        );
    }

    public function testContainsInlineOnNotAddedIsFalse()
    {
        $myItem = new InlineStub();

        $collection = new InlineCollection();
        $collection->add(new InlineStub());
        $collection->add(new InlineStub());
        $collection->add(new InlineStub());

        $this->assertEquals(false, $collection->contains($myItem));
    }

    public function testContainsInlineOnAddedIsTrue()
    {
        $myItem = new InlineStub();

        $collection = new InlineCollection();
        $collection->add(new InlineStub());
        $collection->add($myItem);
        $collection->add(new InlineStub());

        $this->assertEquals(true, $collection->contains($myItem));
    }

    public function testRemoveInlineOnNotAddedIsFalse()
    {
        $myItem = new InlineStub();

        $collection = new InlineCollection();
        $collection->add(new InlineStub());
        $collection->add(new InlineStub());
        $collection->add(new InlineStub());

        $this->assertEquals(false, $collection->remove($myItem));
    }

    public function testRemoveInlineOnAddedIsTrue()
    {
        $myItem = new InlineStub();

        $collection = new InlineCollection();
        $collection->add(new InlineStub());
        $collection->add($myItem);
        $collection->add(new InlineStub());

        $this->assertEquals(true, $collection->remove($myItem));
    }

    public function testCountInlinesOnEmpty()
    {
        $collection = new InlineCollection();

        $this->assertEquals(0, count($collection));
    }

    public function testCountOnNotEmpty()
    {
        $collection = new InlineCollection();
        $collection->add(new InlineStub());
        $collection->add(new InlineStub());
        $collection->add(new InlineStub());

        $this->assertEquals(3, count($collection));
    }

    public function testForeachInlinesOnEmpty()
    {
        $collection = new InlineCollection();

        foreach ($collection as $inline) {
            $this->assertInstanceOf(
                $this->inlineClass,
                $inline
            );
        }

        $this->assertInstanceOf(
            $this->inlineCollectionClass,
            new InlineCollection()
        );
    }

    public function testForeachInlinesOnNotEmpty()
    {
        $collection = new InlineCollection();
        $expectedInlines = [];
        $expectedInlines[] = $collection->add(new InlineStub());
        $expectedInlines[] = $collection->add(new InlineStub());

        $inlines = [];
        foreach ($collection as $key => $inline) {
            $this->assertInstanceOf(
                $this->inlineClass,
                $inline
            );
            $inlines[] = $inline;
        }

        $this->assertEquals($expectedInlines, $inlines);
    }
}
