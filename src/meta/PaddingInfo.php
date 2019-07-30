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

use trogon\otuspdf\meta\UnitInfo;

class PaddingInfo extends \trogon\otuspdf\base\DependencyObject
{
    public $unitInfo;
    public $left;
    public $top;
    public $right;
    public $bottom;

    public static function create($left, $top, $right, $bottom, UnitInfo $unitInfo = null)
    {
        return new PaddingInfo([
            'unitInfo' => $unitInfo,
            'left' => $left,
            'top' => $top,
            'right' => $right,
            'bottom' => $bottom,
        ]);
    }

    public static function createByCss($top, $right, $bottom, $left, UnitInfo $unitInfo = null)
    {
        return new PaddingInfo([
            'unitInfo' => $unitInfo,
            'left' => $left,
            'top' => $top,
            'right' => $right,
            'bottom' => $bottom,
        ]);
    }

    public static function pageNormal()
    {
        return self::create(2.54, 2.54, 2.54, 2.54, UnitInfo::cm());
    }

    public static function pageNarrow()
    {
        return self::create(1.27, 1.27, 1.27, 1.27, UnitInfo::cm());
    }

    public static function pageModerate()
    {
        return self::create(1.91, 2.54, 1.91, 2.54, UnitInfo::cm());
    }

    public static function pageWide()
    {
        return self::create(5.08, 2.54, 5.08, 2.54, UnitInfo::cm());
    }
}
