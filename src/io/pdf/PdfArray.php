<?php namespace insma\otuspdf\io\pdf;

class PdfArray extends \insma\otuspdf\base\BaseObject
{
    private $items = [];

    public function getItems()
    {
        return $this->items;
    }

    public function addItem($value)
    {
        $this->items[] = $value;
    }

    public function toString()
    {
        $content = "[";
        foreach ($this->items as $value) {
            if (strlen($content) > 1) {
                $content .= ' ';
            }
            $content .= $value->toString();
        }
        $content .= "]";
        return $content;
    }
}
