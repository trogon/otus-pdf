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
use trogon\otuspdf\io\TextRender;
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
    private $textRender;

    public function __construct($contentBuilder, $fontRender, $pageContentBox)
    {
        $this->contentBuilder = $contentBuilder;
        $this->fontRender = $fontRender;
        $this->pageContentBox = $pageContentBox;
        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $this->remainingBox = $this->pageContentBox;
        $this->textRender = new TextRender();
        $this->defaultInlineInfo = new InlineInfo([
            'fontFamily' => 'Times-Roman',
            'fontSize' => 14
        ]);
    }

    public function computeTextIndent($blockInfo)
    {
        return isset($blockInfo->textIndent)
            ? $blockInfo->textIndent * 72
            : null;
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

    public function updateRemainingBox($width, $height)
    {
        $this->remainingBox = BlockRender::updateRemainingBox(
            $this->remainingBox,
            $width,
            $height
        );
    }

    public function renderInlines($inlines, $blockBox, $blockInfo)
    {
        $cb = $this->contentBuilder;
        $this->remainingBox = $blockBox;
        $this->textRender->init();

        $fontSize = null;
        $fontFamily = null;
        $fontKey = null;
        $fontData = null;
        $textIndent = $this->computeTextIndent($blockInfo);

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

            if ($inlineNo == 0) {
                $startPosition = $this->computeTextStartPosition($blockBox, $fontSize, $textIndent);
                $content .= $cb->setTextPosition($startPosition->x, $startPosition->y);
                $this->updateRemainingBox(0, $fontSize);
            }
            $content .= $this->renderInlineText($inline->text, $blockBox, $fontSize, $fontData, $textIndent);
        }
        $content .= $cb->endText();

        return $content;
    }

    private function renderInlineText($text, $blockBox, $fontSize, $fontData, $textIndent)
    {
        $cb = $this->contentBuilder;
        $content = $cb->beginTextRender();
        $this->textRender->setMaxWidth($blockBox->width - intval($textIndent));
        $subLines = $this->textRender->wrapText($text, $fontSize, $fontData);
        foreach ($subLines as $subLineNo => $subLine) {
            if ($subLineNo != 0) {
                $content .= $cb->endTextRender();
                $content .= $cb->newLine();
                if (!is_null($textIndent)) {
                    $content .= $cb->setTextPosition(-$textIndent, 0, true);
                    $this->textRender->setMaxWidth($blockBox->width);
                    $textIndent = null;
                }
                $this->updateRemainingBox(0, $fontSize);
                $content .= $cb->beginTextRender();
            }
            $content .= $subLine;
        }
        $content .= $cb->endTextRender();
        return $content;
    }

    public static function computeTextStartPosition($blockBox, $fontSize, $textIndent)
    {
        return new PositionInfo(
            intval($blockBox->x) + intval($textIndent),
            intval($blockBox->y - $fontSize)
        );
    }
}
