<?php namespace insma\otuspdf\io\pdf;

class PdfDictionary extends \insma\otuspdf\base\BaseObject
{
    private $items;

    public function getItems()
    {
        return $this->items;
    }

    public function addItem($key, $value)
    {
        $this->items[] = [$key, $value];
    }

    public function toString()
    {
        $content = "<< ";
        if (!empty($this->items)) {
            foreach ($this->items as $key => $value) {
                $content .= $value[0]->toString() . ' ' . $value[1]->toString() . "\n";
            }
        }
        $content .= ">>";
        return $content;
    }
}
