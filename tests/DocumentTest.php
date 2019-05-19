<?php

use PHPUnit\Framework\TestCase;

use insma\otuspdf\Document;
use insma\otuspdf\base\InvalidCallException;

final class DocumentTest extends TestCase
{
    private $documentClass = 'insma\otuspdf\Document';
    private $invalidCallExceptionClass = 'insma\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->documentClass,
            new Document()
        );
    }

    public function testCanBeCreatedFromAllConfig()
    {
        $this->assertInstanceOf(
            $this->documentClass,
            new Document([
                'title' => 'Test title',
                'author' => 'Test author',
                'subject' => 'Test subject',
                'keywords' => 'Test, test keyword'
            ])
        );
    }

    public function testCannotBeCreatedFromCreationDate()
    {
        $this->expectException($this->invalidCallExceptionClass);

        new Document(['creationDate' => '2018-02-01']);
    }

    public function testCannotBeCreatedFromModificationDate()
    {
        $this->expectException($this->invalidCallExceptionClass);

        new Document(['modificationDate' => '2018-02-01']);
    }
}
