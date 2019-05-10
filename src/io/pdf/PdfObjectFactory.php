<?php namespace insma\otuspdf\io\pdf;

use insma\otuspdf\io\pdf\PdfObject;

class PdfObjectFactory extends \insma\otuspdf\base\BaseObject
{
    private $nextId = 1;

    public function create()
    {
        return new PdfObject($this->nextId++);
    }
}
