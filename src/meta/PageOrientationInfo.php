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

class PageOrientationInfo extends \trogon\otuspdf\base\DependencyObject
{
    const PORTRAIT = 1;
    const LANDSCAPE = 2;

    private $orientation;

    private function __construct($orientation)
    {
        $this->orientation = $orientation;
        parent::__construct();
    }

    public function isPortrait()
    {
        return $this->orientation === self::PORTRAIT;
    }

    public function isLandscape()
    {
        return $this->orientation === self::LANDSCAPE;
    }

    private static $portraitOrientationInfo;
    private static $landscapeOrientationInfo;

    public static function getPortrait()
    {
        if (empty(self::$portraitOrientationInfo)) {
            self::$portraitOrientationInfo = new PageOrientationInfo(self::PORTRAIT);
        }
        return self::$portraitOrientationInfo;
    }

    public static function getLandscape()
    {
        if (empty(self::$landscapeOrientationInfo)) {
            self::$landscapeOrientationInfo = new PageOrientationInfo(self::LANDSCAPE);
        }
        return self::$landscapeOrientationInfo;
    }
}
