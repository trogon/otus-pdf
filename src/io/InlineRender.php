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

use trogon\otuspdf\base\ChainIterator;
use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\meta\PositionInfo;
use trogon\otuspdf\meta\RectInfo;
use trogon\otuspdf\meta\InlineInfo;

class InlineRender extends \trogon\otuspdf\base\DependencyObject
{
    private $contentBuilder;
    private $defaultInlineInfo;
    private $fontRender;
    private $pageContentBox;
    private $remainingBox;

    public function __construct($contentBuilder, $fontRender, $pageContentBox)
    {
        $this->contentBuilder = $contentBuilder;
        $this->fontRender = $fontRender;
        $this->pageContentBox = $pageContentBox;
        $this->remainingBox = $pageContentBox;
        $this->defaultInlineInfo = new InlineInfo([
            'fontFamily' => 'Times-Roman',
            'fontSize' => 14
        ]);
    }

    public function getInlineInfo($inline)
    {
        $mergedConfig = array_merge(
            $this->defaultInlineInfo->toDictionary(),
            array_filter($inline->info->toDictionary())
        );
        $mergedInlineInfo = new InlineInfo($mergedConfig);
        return $mergedInlineInfo;
    }

    public function resetRemainingBox()
    {
        $this->remainingBox = $this->pageContentBox;
    }

    public function getRemainingBox()
    {
        return $this->remainingBox;
    }

    public function updateRemainingBox($fontSize)
    {
        $this->remainingBox = new RectInfo(
            $this->remainingBox->x,
            $this->remainingBox->y - $fontSize,
            $this->remainingBox->width,
            $this->remainingBox->height - $fontSize,
        );
    }

    public function renderInlines($inlines, $blockBox)
    {
        $cb = $this->contentBuilder;

        $fontSize = null;
        $fontFamily = null;
        $fontKey = null;
        $fontData = null;
        
        $content = $cb->beginText();
        foreach ($inlines as $inlineNo => $inline) {
            $inlineInfo = $this->getInlineInfo($inline);
            if ($inlineInfo->fontFamily !== $fontFamily || $inlineInfo->fontSize !== $fontSize) {
                if ($inlineInfo->fontFamily !== $fontFamily) {
                    $fontFamily = $inlineInfo->fontFamily;
                    $fontKey = $this->fontRender->findFontKey($fontFamily);
                    $fontData = $this->fontRender->findFontData($fontKey);
                }
                if ($inlineInfo->fontSize !== $fontSize) {
                    $fontSize = $inlineInfo->fontSize;
                    $content .= $cb->setTextLeading($fontSize);
                }
                $content .= $cb->setFont($fontKey, $fontSize);
            }

            if ($inlineNo != 0) {
                $content .= $cb->newLine();
                $this->updateRemainingBox($fontSize);
            } else {
                $startPosition = $this->computeTextStartPosition($blockBox, $fontSize);
                $content .= $cb->setTextPosition($startPosition->x, $startPosition->y);
                $this->updateRemainingBox($fontSize);
            }
            $content .= $this->renderTextLines($inline, $blockBox, $fontSize, $fontData);
        }
        $content .= $cb->endText();

        return $content;
    }

    private function renderTextLines($inline, $blockBox, $fontSize, $fontData)
    {
        $cb = $this->contentBuilder;
        $content = '';
        $lines = \explode("\n", $inline->text);
        foreach ($lines as $lineNo => $textLine) {
            if ($lineNo != 0) {
                $content .= $cb->newLine();
                $this->updateRemainingBox($fontSize);
            }
            $content .= $cb->beginTextRender();
            $content .= $this->wrapText($textLine, $blockBox, $fontSize, $fontData);
            $content .= $cb->endTextRender();
        }
        return $content;
    }

    private function wrapText($textLine, $blockBox, $fontSize, $fontData)
    {
        $cb = $this->contentBuilder;
        $content = '';
        $words = \explode(" ", $textLine);
        $textLineWidth = 0;
        $spaceWidth = $this->findCharHorizontalLength(ord(' '), $fontSize, $fontData);
        foreach ($words as $key => $textWord) {
            $wordWidth = $this->computeHorizontalLength($textWord, $fontSize, $fontData);
            $textLineWidth += $wordWidth;
            if ($key != 0) {
                $textLineWidth += $spaceWidth;
            }
            if ($textLineWidth > $blockBox->width) {
                $textLineWidth = $wordWidth;
                $content .= $cb->endTextRender();
                $content .= $cb->newLine();
                $this->updateRemainingBox($fontSize);
                $content .= $cb->beginTextRender();
            } elseif ($key != 0) {
                $content .= ' ';
            }
            $content .= $textWord;
        }
        return $content;
    }

    public function computeTextStartPosition($blockBox, $fontSize)
    {
        return new PositionInfo(
            intval($blockBox->x),
            intval($blockBox->y - $fontSize)
        );
    }

    private function computeHorizontalLength($text, $fontSize, $fontData)
    {
        $chars = mb_split('\X\K(?!$)', $text);
        $width = 0;
        foreach ($chars as $char) {
            $charCode = $this->mb_ord($char); // Method available since PHP 7.0 :(
            $width += $this->findCharHorizontalLength($charCode, $fontSize, $fontData);
        }
        return $width;
    }

    private function findCharHorizontalLength($charCode, $fontSize, $fontData)
    {
        if (array_key_exists($charCode, $fontData->metrics)) {
            return ($fontData->metrics[$charCode]) * $fontSize / 1000;
        } else {
            return 72 / $fontSize;
        }
    }

    private function mb_ord($char)
    {
        $charCode = 0;
        $bytes = str_split($char);
        foreach ($bytes as $byte) {
            $charCode = ($charCode << 8) + ord($byte);
        }
        return $charCode;
    }
}
