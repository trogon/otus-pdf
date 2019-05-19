<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use insma\otuspdf\Document;

final class DocumentTest extends TestCase
{
    public function testCanBeCreatedFromEmptyConfig(): void
    {
        $this->assertInstanceOf(
            Document::class,
            new Document()
        );
    }

    public function testCanBeCreatedFromAllConfig(): void
    {
        $this->assertInstanceOf(
            Document::class,
            new Document([
                'title' => 'Test title',
                'author' => 'Test author',
                'subject' => 'Test subject',
                'keywords' => 'Test, test keyword'
            ])
        );
    }

    public function testCannotBeCreatedFromCreationDate(): void
    {
        $this->expectException(\insma\otuspdf\base\InvalidCallException::class);

        new Document(['creationDate' => '2018-02-01']);
    }

    public function testCannotBeCreatedFromModificationDate(): void
    {
        $this->expectException(\insma\otuspdf\base\InvalidCallException::class);

        new Document(['modificationDate' => '2018-02-01']);
    }
}
