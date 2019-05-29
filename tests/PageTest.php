<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\Page;
use insma\otuspdf\base\InvalidCallException;

final class PageTest extends TestCase
{
    private $pageClass = 'insma\otuspdf\Page';
    private $pageInfoClass = 'insma\otuspdf\meta\PageInfo';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pageClass,
            new Page()
        );
    }

    public function testCanBeCreatedFromAllConfig()
    {
        $this->assertInstanceOf(
            $this->pageClass,
            new Page([
                'orientation' => 'Landscape',
                'size' => 'A4'
            ])
        );
    }

    public function testReturnsInfoWhenNotConfigured()
    {
        $this->assertInstanceOf(
            $this->pageInfoClass,
            (new Page())->info
        );
    }

    public function testReturnsInfoWhenConfigured()
    {
        $this->assertInstanceOf(
            $this->pageInfoClass,
            (new Page([
                'orientation' => 'Landscape',
                'size' => 'A4'
            ]))->info
        );
    }
}
