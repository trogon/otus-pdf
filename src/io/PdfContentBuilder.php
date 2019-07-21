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
namespace trogon\otuspdf\io;

use trogon\otuspdf\base\InvalidCallException;

class PdfContentBuilder extends \trogon\otuspdf\base\BaseObject
{
    const NONE_STATE = 0;
    const TEXT_STATE = 1;
    const TEXT_RENDER_STATE = 2;
    const PATH_STATE = 4;
    const SHADING_STATE = 8;
    const EXTERNAL_STATE = 16;
    const IMAGE_STATE = 32;

    private $currentState;

    public function __construct()
    {
        $this->currentState = self::NONE_STATE;
    }

    public function checkState($state)
    {
        if ($state === $this->currentState) {
            return true;
        } else {
            throw new InvalidCallException("Not expected state found {$this->currentState}, expected {$state}.");
            return false;
        }
    }

    public function setState($state)
    {
        $this->currentState = $state;
    }

    public function beginText()
    {
        $this->checkState(self::NONE_STATE);
        $this->setState(self::TEXT_STATE);
        return "BT\n";
    }

    public function beginTextRender()
    {
        $this->checkState(self::TEXT_STATE);
        $this->setState(self::TEXT_RENDER_STATE);
        return "\t (";
    }

    public function endText()
    {
        $this->checkState(self::TEXT_STATE);
        $this->setState(self::NONE_STATE);
        return "ET\n";
    }

    public function endTextRender()
    {
        $this->checkState(self::TEXT_RENDER_STATE);
        $this->setState(self::TEXT_STATE);
        return ") Tj\n";
    }

    public function newLine()
    {
        $this->checkState(self::TEXT_STATE);
        return "\t T*\n";
    }

    public function setFont($fontKey, $fontSize)
    {
        $this->checkState(self::TEXT_STATE);
        return "\t /$fontKey $fontSize Tf\n";
    }

    public function setTextColorRgb($r, $g, $b)
    {
        $this->checkState(self::TEXT_STATE);
        $r /= 255.0;
        $g /= 255.0;
        $b /= 255.0;
        return "\t $r $g $b rg\n";
    }

    public function setTextLeading($fontSize)
    {
        $this->checkState(self::TEXT_STATE);
        return "\t $fontSize TL\n";
    }

    public function setTextPosition($x, $y)
    {
        $this->checkState(self::TEXT_STATE);
        return "\t $x $y Td\n";
    }
}
