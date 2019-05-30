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
namespace insma\otuspdf\meta;

use insma\otuspdf\base\InvalidCallException;

class PageSizeInfo extends \insma\otuspdf\base\BaseObject
{
    private $height;
    private $width;

    public function __construct($width, $height)
    {
        if ($width < $height) {
            throw new InvalidCallException('Width is shorter than height. Page size defines a size for landscape orientation.');
        }
        $this->height = $height;
        $this->width = $width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public static function getLetter()
    {
        return new PageSizeInfo(11, 8.5);
    }

    public static function getTabloid()
    {
        return new PageSizeInfo(17, 11);
    }

    public static function getLegal()
    {
        return new PageSizeInfo(14, 8.5);
    }

    public static function getStatement()
    {
        return new PageSizeInfo(8.5, 5.5);
    }

    public static function getExecutive()
    {
        return new PageSizeInfo(10.5, 7.25);
    }

    public static function getA3()
    {
        return new PageSizeInfo(16.5, 11.7);
    }

    public static function getA4()
    {
        return new PageSizeInfo(11.7, 8.27);
    }

    public static function getA5()
    {
        return new PageSizeInfo(8.27, 5.83);
    }

    public static function getB4Jis()
    {
        return new PageSizeInfo(14.33, 10.12);
    }

    public static function getB5Jis()
    {
        return new PageSizeInfo(10.12, 7.17);
    }
}