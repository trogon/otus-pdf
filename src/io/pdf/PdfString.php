<?php
/**
 * Otus PDF - PDF document generation library
 * Copyright(C) 2019 Maciej Klemarczyk
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */
namespace trogon\otuspdf\io\pdf;

class PdfString extends \trogon\otuspdf\base\DependencyObject
{
    const TYPE_LITERAL = 0;
    const TYPE_HEX = 1;

    private $type;
    private $value;

    public function init()
    {
        parent::init();
        if (!in_array($this->type, [self::TYPE_LITERAL, self::TYPE_HEX])) {
            $this->type = self::TYPE_LITERAL;
        }
    }

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
