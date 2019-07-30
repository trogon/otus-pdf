<?php
namespace trogon\otuspdf\test\fixture;

use trogon\otuspdf\base\ContentElement;

final class ContentElementDummy extends ContentElement
{
    protected function createInfo($config)
    {
        return $config;
    }
}
