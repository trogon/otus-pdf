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

class UnitInfo extends \trogon\otuspdf\base\BaseObject
{
    const MM_UNIT = 'mm';
    const CM_UNIT = 'cm';
    const INCH_UNIT = 'inch';
    const INCH_TO_CM_FACTOR = 2.54;
    const CM_TO_MM_FACTOR = 10.0;

    private $unit;

    public function toMm($value)
    {
        switch ($this->unit) {
            case self::MM_UNIT:
                return $value;
            case self::CM_UNIT:
                return $value * self::CM_TO_MM_FACTOR;
            case self::INCH_UNIT:
                return $value * self::CM_TO_MM_FACTOR * self::INCH_TO_CM_FACTOR;
        }
    }

    public function toCm($value)
    {
        switch ($this->unit) {
            case self::MM_UNIT:
                return $value / self::CM_TO_MM_FACTOR;
            case self::CM_UNIT:
                return $value;
            case self::INCH_UNIT:
                return $value * self::INCH_TO_CM_FACTOR;
        }
    }

    public function toInch($value)
    {
        switch ($this->unit) {
            case self::MM_UNIT:
                return $value / self::INCH_TO_CM_FACTOR / self::CM_TO_MM_FACTOR;
            case self::CM_UNIT:
                return $value / self::INCH_TO_CM_FACTOR;
            case self::INCH_UNIT:
                return $value;
        }
    }

    private static function create($unit)
    {
        $unitInfo = new UnitInfo();
        $unitInfo->unit = $unit;
        return $unitInfo;
    }

    public static function mm()
    {
        return self::create(self::MM_UNIT);
    }

    public static function cm()
    {
        return self::create(self::CM_UNIT);
    }

    public static function inch()
    {
        return self::create(self::INCH_UNIT);
    }
}
