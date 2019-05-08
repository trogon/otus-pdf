<?php namespace insma\otuspdf\io\pdf;

class PdfNumber extends \insma\otuspdf\base\BaseObject
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

    public function toString()
    {
        $content = $this->value;
        return $content;
    }
}
