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
use trogon\otuspdf\meta\TextInfo;

class TextRender extends \trogon\otuspdf\base\BaseObject
{
    public function renderTextItems($textItems, $pageInfo, $fontRender)
    {
        $defaultTextInfo = new TextInfo([
            'fontFamily' => 'Times-Roman',
            'fontSize' => 14
        ]);
        $fontSize = $defaultTextInfo->fontSize;
        $fontFamily = $defaultTextInfo->fontFamily;
        $fontKey = $fontRender->findFontKey($fontFamily);

        $startPosition = $this->computeTextStartPosition($pageInfo);
        $x = intval($startPosition->x);
        $y = intval($startPosition->y -$fontSize);

        $content = "BT\n";
        $content .= "\t /{$fontKey} {$fontSize} Tf\n";
        $content .= "\t {$fontSize} TL\n";
        $content .= "\t {$x} {$y} Td\n";
        foreach ($textItems as $textNo => $text) {
            $textInfo = new TextInfo(array_merge(
                $defaultTextInfo->toDictionary(),
                array_filter($text->info->toDictionary())
            ));
            $fontSize = $textInfo->fontSize;
            $fontFamily = $textInfo->fontFamily;
            $fontKey = $fontRender->findFontKey($fontFamily);
            $content .= "\t /{$fontKey} {$fontSize} Tf\n";
            $content .= "\t {$fontSize} TL\n";
            if ($textNo != 0) {
                $content .= "\t T*\n";
            }
            $lines = \explode("\n", $text->text);
            foreach ($lines as $lineNo => $textLine) {
                if ($lineNo != 0) {
                    $content .= "\t T*\n";
                }
                $content .= "\t ({$textLine}) Tj\n";
            }
        }
        $content .= "ET";

        return $content;
    }

    private function computeTextStartPosition($pageInfo)
    {
        $pageMargin = $pageInfo->margin;
        $unitInfo = $pageMargin->unitInfo;
        $leftMargin = $unitInfo->toInch($pageMargin->left);
        $topMargin = $unitInfo->toInch($pageMargin->top);

        $pageSize = $pageInfo->size;
        $unitInfo = $pageSize->unitInfo;

        if ($pageInfo->orientation->isLandscape()) {
            $top = $unitInfo->toInch($pageSize->height);
            return new PositionInfo($leftMargin * 72, ($top - $topMargin) * 72);
        } else {
            $top = $unitInfo->toInch($pageSize->width);
            return new PositionInfo($leftMargin * 72, ($top - $topMargin) * 72);
        }
    }
}
