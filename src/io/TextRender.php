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
use trogon\otuspdf\meta\PositionInfo;

class TextRender extends \trogon\otuspdf\base\BaseObject
{
    private $defaultFontSize = 14;

    public function renderTextItems($textItems, $pageOrientation, $pageSize)
    {
        $fontSize = $this->defaultFontSize;
        $startPosition = $this->computeTextStartPosition($pageOrientation, $pageSize);
        $x = intval($startPosition->x);
        $y = intval($startPosition->y -$fontSize);

        $content = "BT\n";
        $content .= "\t /F1 {$fontSize} Tf\n";
        $content .= "\t {$fontSize} TL\n";
        $content .= "\t {$x} {$y} Td\n";
        foreach ($textItems as $text) {
            $lines = \explode("\n", $text->text);
            foreach ($lines as $textLine) {
                $content .= "\t ({$textLine}) Tj\n";
                $content .= "\t T*\n";
            }
        }
        $content .= "ET";

        return $content;
    }

    private function computeTextStartPosition($pageOrientation, $pageSize)
    {
        if ($pageOrientation->isLandscape()) {
            return new PositionInfo(0.0, $pageSize->height *72);
        } else {
            return new PositionInfo(0.0, $pageSize->width *72);
        }
    }
}
