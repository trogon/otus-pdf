<?php
namespace trogon\otuspdf\test\fixture;

final class ProviderStub
{
    public static $document;

    public function __construct($document)
    {
        self::$document = $document;
    }

    public function save($filename)
    {
        return true;
    }

    public function toString()
    {
        return 'Simple provider text';
    }
}
