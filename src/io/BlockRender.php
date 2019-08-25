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

use trogon\otuspdf\base\InvalidOperationException;
use trogon\otuspdf\meta\RectInfo;
use trogon\otuspdf\io\InlineRender;
use trogon\otuspdf\io\PdfContentBuilder;
use trogon\otuspdf\PageBreak;
use trogon\otuspdf\Paragraph;
use trogon\otuspdf\Table;
use trogon\otuspdf\TextBlock;

class BlockRender extends \trogon\otuspdf\base\DependencyObject
{
    private $contentBuilder;
    private $fontRender;

    public function __construct($fontRender)
    {
        $this->fontRender = $fontRender;
        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $this->contentBuilder = new PdfContentBuilder();
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
            RectInfo::INVERT_VERTICAL
        );
    }

    public function computeParagraphBox($paragraphInfo, $pageContentBox)
    {
        return $pageContentBox;
    }

    public function computeTableBox($tableInfo, $pageContentBox)
    {
        return $pageContentBox;
    }

    public static function updateRemainingBox($box, $width, $height)
    {
        return new RectInfo(
            $box->x + $width,
            $box->y - $height,
            $box->width - $width,
            $box->height - $height,
            $box->orientation
        );
    }

    public function renderBlocks($blocks, $pageInfo)
    {
        $pageContentBox = $this->computePageContentBox($pageInfo);
        $remainingBox = $pageContentBox;
        $inlineRender = new InlineRender(
            $this->contentBuilder,
            $this->fontRender,
            $pageContentBox
        );

        $content = '';
        $blockBox = null;
        foreach ($blocks as $block) {
            if ($block instanceof TextBlock) {
                $blockBox = $remainingBox;
                $content .= $inlineRender->renderInlines($block->inlines, $blockBox, $block->info);
                $remainingBox = $inlineRender->remainingBox;
            } elseif ($block instanceof Paragraph) {
                $blockBox = $this->computeParagraphBox($block->info, $remainingBox);
                $content .= $inlineRender->renderInlines($block->inlines, $blockBox, $block->info);
                $remainingBox = $inlineRender->remainingBox;
            } elseif ($block instanceof PageBreak) {
                yield $content;
                $content = '';
                $remainingBox = $pageContentBox;
                $inlineRender->resetRemainingBox();
            } elseif ($block instanceof Table) {
                $tableRender = new TableRender(
                    $this->contentBuilder,
                    $this->fontRender,
                    $pageContentBox
                );
                $blockBox = $this->computeTableBox($block->info, $remainingBox);
                $content .= $tableRender->render($block, $blockBox);
                $remainingBox = $tableRender->remainingBox;
            }
        }
        if (!empty($content)) {
            yield $content;
        }
    }
}
