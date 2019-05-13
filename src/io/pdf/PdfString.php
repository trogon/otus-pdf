<?php namespace insma\otuspdf\io\pdf;

class PdfString extends \insma\otuspdf\base\BaseObject
{
    public const TYPE_LITERAL = 0;
    public const TYPE_HEX = 1;

    private $type = self::TYPE_LITERAL;
    private $value;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

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
        if ($this->type === self::TYPE_HEX) {
            $content = '<' . $this->value . '>';
        } else {
            $content = '(' . $this->value . ')';
        }
        return $content;
    }
}
