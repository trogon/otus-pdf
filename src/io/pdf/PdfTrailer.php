<?php namespace insma\otuspdf\io\pdf;

use insma\otuspdf\io\pdf\PdfDictionary;

class PdfTrailer extends \insma\otuspdf\base\BaseObject
{
    private $xrefOffset;
    private $content;

    public function __construct($config = [])
    {
        $this->content = new PdfDictionary();
        parent::__construct($config);
    }

    public function getXrefOffset()
    {
        return $this->xrefOffset;
    }

    public function setXrefOffset($xrefOffset)
    {
        $this->xrefOffset = $xrefOffset;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function toString()
    {
        $content = "trailer\n";
        $content .= $this->content->toString() . "\n";
        $content .= "startxref\n";
        $content .= "$this->xrefOffset";
        return $content;
    }
}
