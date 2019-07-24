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
use trogon\otuspdf\meta\RectInfo;
use trogon\otuspdf\io\InlineRender;
use trogon\otuspdf\io\PdfContentBuilder;
use trogon\otuspdf\PageBreak;
use trogon\otuspdf\Paragraph;
use trogon\otuspdf\TextBlock;

class BlockRender extends \trogon\otuspdf\base\DependencyObject
{
    private $contentBuilder;
    private $defaultTextInfo;
    private $fontRender;

    public function __construct($fontRender)
    {
        $this->contentBuilder = new PdfContentBuilder();
        $this->fontRender = $fontRender;
    }

    public function computePageContentBox($pageInfo)
    {
        $unitInfo = $pageInfo->margin->unitInfo;
        $leftMargin = $unitInfo->toInch($pageInfo->margin->left);
        $topMargin = $unitInfo->toInch($pageInfo->margin->top);
        $rightMargin = $unitInfo->toInch($pageInfo->margin->right);
        $bottomMargin = $unitInfo->toInch($pageInfo->margin->bottom);

        $unitInfo = $pageInfo->size->unitInfo;
        if ($pageInfo->orientation->isLandscape()) {
            $height = $unitInfo->toInch($pageInfo->size->height);
            $width = $unitInfo->toInch($pageInfo->size->width);
        } else {
            $height = $unitInfo->toInch($pageInfo->size->width);
            $width = $unitInfo->toInch($pageInfo->size->height);
        }

        return new RectInfo(
            $leftMargin * 72,
            ($height - $topMargin) * 72,
            ($width - $leftMargin - $rightMargin) * 72,
            ($height - $topMargin - $bottomMargin) * 72,
        );
    }

    public function computeParagraphBox($paragraphInfo, $pageContentBox)
    {
        return $pageContentBox;
    }

    public function renderBlocks($blocks, $pageInfo)
    {
        $pageContentBox = $this->computePageContentBox($pageInfo);
        $inlineRender = new InlineRender(
            $this->contentBuilder,
            $this->fontRender,
            $pageContentBox
        );

        $inlines = new ChainIterator();
        $blockBox = null;
        foreach ($blocks as $block) {
            if ($block instanceof TextBlock) {
                $blockBox = $this->computeParagraphBox($block->info, $pageContentBox);
                $inlines->append($block->inlines->iterator);
            } elseif ($block instanceof Paragraph) {
                if (count($inlines->getArrayIterator()) != 0) {
                    yield $inlineRender->renderInlines($inlines, $blockBox);
                }
                $inlines = new ChainIterator();
                $blockBox = $this->computeParagraphBox($block->info, $pageContentBox);
                yield $inlineRender->renderInlines($block->inlines, $blockBox);
            } elseif ($block instanceof PageBreak) {
                yield $inlineRender->renderInlines($inlines, $blockBox);
                $inlines = new ChainIterator();
                $blockBox = $pageContentBox;
            }
        }
        if (count($inlines->getArrayIterator()) != 0) {
            yield $inlineRender->renderInlines($inlines, $blockBox);
        }
    }
}
