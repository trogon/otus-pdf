<?php namespace insma\otuspdf\io\pdf;

class PdfStream extends \insma\otuspdf\base\BaseObject
{
    private $value;

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getLength()
    {
        return strlen($this->value);
    }

    public function toString()
    {
        $content = "stream\n";
        $content .= $this->value . "\n";
        $content .= "endstream";
        return $content;
    }
}
