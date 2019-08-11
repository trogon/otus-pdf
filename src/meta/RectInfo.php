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
namespace trogon\otuspdf\meta;

class RectInfo extends \trogon\otuspdf\base\DependencyObject
{
    const INVERT_NONE = 0;
    const INVERT_HORIZONTAL = 1;
    const INVERT_VERTICAL = 2;
    const INVERT_ALL = self::INVERT_HORIZONTAL | self::INVERT_VERTICAL;

    private $x;
    private $y;
    private $width;
    private $height;
    private $orientation;
    private $horizontalInvert;
    private $verticalInvert;

    public function __construct($x, $y, $width, $height, $orientation = self::INVERT_NONE)
    {
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
        $this->orientation = $orientation;
        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $hasHorizontalInvert = (self::INVERT_HORIZONTAL & $this->orientation) !== 0;
        $hasVerticalInvert = (self::INVERT_VERTICAL & $this->orientation) !== 0;
        $this->horizontalInvert = $hasHorizontalInvert ? -1.0 : 1.0;
        $this->verticalInvert = $hasVerticalInvert ? -1.0 : 1.0;
    }

    public function getBottom()
    {
        return $this->y + $this->height * $this->verticalInvert;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getOrientation()
    {
        return $this->orientation;
    }

    public function getLeft()
    {
        return $this->x;
    }

    public function getRight()
    {
        return $this->x + $this->width * $this->horizontalInvert;
    }

    public function getTop()
    {
        return $this->y;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }
}
