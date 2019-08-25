<?php
namespace trogon\otuspdf\test\unit;

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\Block;
use trogon\otuspdf\BlockCollection;
use trogon\otuspdf\base\InvalidOperationException;

use trogon\otuspdf\test\fixture\BlockStub;

/**
 * @covers \trogon\otuspdf\BlockCollection
 */
final class BlockCollectionTest extends TestCase
{
    private $blockCollectionClass = 'trogon\otuspdf\BlockCollection';
    private $blockClass = 'trogon\otuspdf\Block';
    private $invalidOperationExceptionClass = 'trogon\otuspdf\base\InvalidOperationException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->blockCollectionClass,
            new BlockCollection()
        );
    }

    public function testAddReturnsBlock()
    {
        $collection = new BlockCollection();
        $this->assertInstanceOf(
            $this->blockClass,
            $collection->add(new BlockStub())
        );
    }

    public function testContainsBlockOnEmptyIsFalse()
    {
        $myItem = new BlockStub();

        $collection = new BlockCollection();

        $this->assertEquals(false, $collection->contains($myItem));
    }

    public function testContainsBlockOnNotAddedIsFalse()
    {
        $myItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());

        $this->assertEquals(false, $collection->contains($myItem));
    }

    public function testContainsBlockOnAddedIsTrue()
    {
        $myItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add(new BlockStub());
        $collection->add($myItem);
        $collection->add(new BlockStub());

        $this->assertEquals(true, $collection->contains($myItem));
    }

    public function testInsertAfterFirstBlock()
    {
        $myItem = new BlockStub();
        $firstItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add($firstItem);
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());

        $collection->insertAfter($firstItem, $myItem);

        $this->assertSame($myItem, $collection[1]);
    }

    public function testInsertAfterLastBlock()
    {
        $myItem = new BlockStub();
        $lastItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());
        $collection->add($lastItem);

        $collection->insertAfter($lastItem, $myItem);

        $this->assertSame($myItem, $collection[3]);
    }

    public function testInsertAfterNotExistingBock()
    {
        $myItem = new BlockStub();
        $notAddedItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());

        $this->assertEquals(false, $collection->insertAfter($notAddedItem, $myItem));
    }

    public function testInsertBeforeFirstBlock()
    {
        $myItem = new BlockStub();
        $firstItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add($firstItem);
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());

        $collection->insertBefore($firstItem, $myItem);

        $this->assertSame($myItem, $collection[0]);
    }

    public function testInsertBeforeLastBlock()
    {
        $myItem = new BlockStub();
        $lastItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());
        $collection->add($lastItem);

        $collection->insertBefore($lastItem, $myItem);

        $this->assertSame($myItem, $collection[2]);
    }

    public function testInsertBeforeNotExistingBock()
    {
        $myItem = new BlockStub();
        $notAddedItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());

        $this->assertEquals(false, $collection->insertBefore($notAddedItem, $myItem));
    }

    public function testRemoveBlockOnNotAddedIsFalse()
    {
        $myItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());

        $this->assertEquals(false, $collection->remove($myItem));
    }

    public function testRemoveBlockOnAddedIsTrue()
    {
        $myItem = new BlockStub();

        $collection = new BlockCollection();
        $collection->add(new BlockStub());
        $collection->add($myItem);
        $collection->add(new BlockStub());

        $this->assertEquals(true, $collection->remove($myItem));
    }

    public function testCountBlocksOnEmpty()
    {
        $collection = new BlockCollection();

        $this->assertEquals(count($collection), 0);
    }

    public function testCountOnNotEmpty()
    {
        $collection = new BlockCollection();
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());
        $collection->add(new BlockStub());

        $this->assertEquals(count($collection), 3);
    }

    public function testForeachBlocksOnEmpty()
    {
        $collection = new BlockCollection();

        foreach ($collection as $block) {
            $this->assertInstanceOf(
                $this->blockClass,
                $block
            );
        }

        $this->assertInstanceOf(
            $this->blockCollectionClass,
            new BlockCollection()
        );
    }

    public function testForeachBlocksOnNotEmpty()
    {
        $collection = new BlockCollection();
        $expectedBlocks = [];
        $expectedBlocks[] = $collection->add(new BlockStub());
        $expectedBlocks[] = $collection->add(new BlockStub());

        $blocks = [];
        foreach ($collection as $key => $block) {
            $this->assertInstanceOf(
                $this->blockClass,
                $block
            );
            $blocks[] = $block;
        }

        $this->assertEquals($expectedBlocks, $blocks);
    }
}
