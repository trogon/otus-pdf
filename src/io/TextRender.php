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
use trogon\otuspdf\PageBreak;
use trogon\otuspdf\Text;

class TextRender extends \trogon\otuspdf\base\DependencyObject
{
    private $contentBuilder;
    private $defaultTextInfo;
    private $fontRender;

    public function __construct($fontRender)
    {
        $this->contentBuilder = new PdfContentBuilder();
        $this->fontRender = $fontRender;
        $this->defaultTextInfo = new TextInfo([
            'fontFamily' => 'Times-Roman',
            'fontSize' => 14
        ]);
    }

    public function getTextInfo($text)
    {
        $mergedConfig = array_merge(
            $this->defaultTextInfo->toDictionary(),
            array_filter($text->info->toDictionary())
        );
        $mergedPageInfo = new TextInfo($mergedConfig);
        return $mergedPageInfo;
    }

    public function renderBlockItems($blockItems, $pageInfo)
    {
        $textItems = [];
        foreach ($blockItems as $blockItem) {
            if ($blockItem instanceof Text) {
                $textItems[] = $blockItem;
            } else if ($blockItem instanceof PageBreak) {
                yield $this->renderTextItems($textItems, $pageInfo);
                $textItems = [];
            }
        }
        if (!empty($textItems)) {
            yield $this->renderTextItems($textItems, $pageInfo);
        }
    }

    public function renderTextItems($textItems, $pageInfo)
    {
        $cb = $this->contentBuilder;
        $maxTextWidth = $this->computeMaxTextWidth($pageInfo);

        $fontSize = null;
        $fontFamily = null;
        $fontKey = null;
        $fontData = null;
        
        $content = $cb->beginText();
        foreach ($textItems as $textNo => $text) {
            $textInfo = $this->getTextInfo($text);
            if ($textInfo->fontFamily !== $fontFamily || $textInfo->fontSize !== $fontSize) {
                if ($textInfo->fontFamily !== $fontFamily) {
                    $fontFamily = $textInfo->fontFamily;
                    $fontKey = $this->fontRender->findFontKey($fontFamily);
                    $fontData = $this->fontRender->findFontData($fontKey);
                }
                if ($textInfo->fontSize !== $fontSize) {
                    $fontSize = $textInfo->fontSize;
                    $content .= $cb->setTextLeading($fontSize);
                }
                $content .= $cb->setFont($fontKey, $fontSize);
            }

            if ($textNo != 0) {
                $content .= $cb->newLine();
            } else {
                $startPosition = $this->computeTextStartPosition($pageInfo, $fontSize);
                $content .= $cb->setTextPosition($startPosition->x, $startPosition->y);
            }
            $content .= $this->renderTextLines($text, $maxTextWidth, $fontSize, $fontData);
        }
        $content .= $cb->endText();

        return $content;
    }

    private function renderTextLines($text, $maxTextWidth, $fontSize, $fontData)
    {
        $cb = $this->contentBuilder;
        $content = '';
        $lines = \explode("\n", $text->text);
        foreach ($lines as $lineNo => $textLine) {
            if ($lineNo != 0) {
                $content .= $cb->newLine();
            }
            $content .= $cb->beginTextRender();
            $content .= $this->wrapText($textLine, $maxTextWidth, $fontSize, $fontData);
            $content .= $cb->endTextRender();
        }
        return $content;
    }

    private function wrapText($textLine, $maxTextWidth, $fontSize, $fontData)
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
            if ($textLineWidth > $maxTextWidth) {
                $textLineWidth = $wordWidth;
                $content .= $cb->endTextRender();
                $content .= $cb->newLine();
                $content .= $cb->beginTextRender();
            } elseif ($key != 0) {
                $content .= ' ';
            }
            $content .= $textWord;
        }
        return $content;
    }

    private function computePageTopLeftPosition($pageInfo)
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

    private function computeTextStartPosition($pageInfo, $fontSize)
    {
        $startPosition = $this->computePageTopLeftPosition($pageInfo);
        return new PositionInfo(
            intval($startPosition->x),
            intval($startPosition->y - $fontSize)
        );
    }

    private function computeMaxTextWidth($pageInfo)
    {
        $maxWidth = 0;

        $pageSize = $pageInfo->size;
        $unitInfo = $pageSize->unitInfo;
        if ($pageInfo->orientation->isLandscape()) {
            $maxWidth = $unitInfo->toInch($pageSize->width);
        } else {
            $maxWidth = $unitInfo->toInch($pageSize->height);
        }

        $pageMargin = $pageInfo->margin;
        $unitInfo = $pageMargin->unitInfo;
        $leftMargin = $unitInfo->toInch($pageMargin->left);
        $rightMargin = $unitInfo->toInch($pageMargin->right);

        return ($maxWidth - $leftMargin - $rightMargin) *72;
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
