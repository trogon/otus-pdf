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

class PdfArray extends \trogon\otuspdf\base\DependencyObject
{
    private $items;

    public function init()
    {
        parent::init();
        $this->items = [];
    }

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
