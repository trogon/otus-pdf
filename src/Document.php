<?php namespace insma\otuspdf;

use insma\otuspdf\meta\DocumentInfo;

class Document extends \insma\otuspdf\base\BaseObject
{
    private $info;

    public function __construct($config = [])
    {
        $this->info = new DocumentInfo($config);
    }

    public function getInfo()
    {
        return $this->info;
    }
}
