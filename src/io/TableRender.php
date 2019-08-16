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
use trogon\otuspdf\meta\InlineInfo;
use trogon\otuspdf\meta\PositionInfo;
use trogon\otuspdf\meta\RectInfo;
use trogon\otuspdf\io\InlineRender;
use trogon\otuspdf\io\FontRender;
use trogon\otuspdf\io\PdfContentBuilder;
use trogon\otuspdf\PageBreak;
use trogon\otuspdf\Paragraph;
use trogon\otuspdf\Table;
use trogon\otuspdf\TextBlock;

class TableRender extends \trogon\otuspdf\base\DependencyObject
{
    const TABLE_MARGIN = 9; // exp (72 / 8)

    private $contentBuilder;
    private $defaultInlineInfo;
    private $fontRender;
    private $pageContentBox;
    private $remainingBox;
    private $textRender;

    public function __construct(
        PdfContentBuilder $contentBuilder,
        FontRender $fontRender,
        RectInfo $pageContentBox
    )
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

    public function getRemainingBox()
    {
        return $this->remainingBox;
    }

    public function render(Table $tableBlock, RectInfo $tableBox)
    {
        $inlineRender = new InlineRender(
            $this->contentBuilder,
            $this->fontRender,
            $tableBox
        );

        $cb = $this->contentBuilder;
        $columnBoxes = $this->computeColumnBoxes($tableBlock->columns, $tableBox);
        $columnCount = count($columnBoxes);

        $content = '';
        $content .= $cb->setStrokeColorRgb(24, 16, 182);

        $remainingBox = $tableBox;
        foreach ($tableBlock->rowGroups as $grn => $rowGroup) {
            foreach ($rowGroup->rows as $rn => $row) {
                $cellBoxes = [];
                foreach ($row->cells as $cn => $cell) {
                    $cellBox = $this->computeCellBox($cell->info, $columnBoxes[$cn]);
                    $cellBoxes[$cn] = $cellBox;
                    $cellRemaining = $cellBox;
                    foreach ($cell->blocks as $bn => $block) {
                        if ($block instanceof Paragraph) {
                            $content .= $inlineRender->renderInlines($block->inlines, $cellRemaining, $block->info);
                            $cellRemaining = $inlineRender->remainingBox;
                        }
                    }
                    $cellRemaining = BlockRender::updateRemainingBox($cellRemaining, 0, self::TABLE_MARGIN);
                    $columnBoxes[$cn] = $cellRemaining;
                }
                // Allign columns space
                $minY = $columnBoxes[0]->y;
                foreach ($columnBoxes as $columnBox) {
                    $minY = min($minY, $columnBox->y);
                }
                foreach ($columnBoxes as $cn => $columnBox) {
                    if ($columnBox->y > $minY) {
                        $columnBoxes[$cn] = BlockRender::updateRemainingBox(
                            $columnBox, 0, $columnBox->y - $minY
                        );
                    }
                    if (array_key_exists($cn, $cellBoxes)) {
                        $cellBox = $cellBoxes[$cn];
                        $cellBorderBox = new RectInfo(
                            $cellBox->x - self::TABLE_MARGIN / 2.0,
                            $cellBox->y,
                            $cellBox->width + self::TABLE_MARGIN,
                            $cellBox->top - $minY,
                            $cellBox->orientation
                        );
                        $content .= $cb->drawRectangle($cellBorderBox->x, $cellBorderBox->y, $cellBorderBox->width, -$cellBorderBox->height);
                        $content .= $cb->endPath();
                    }
                }
            }
        }
        $this->remainingBox = new RectInfo(
            $tableBox->x,
            $columnBoxes[0]->y,
            $tableBox->width,
            $columnBoxes[0]->height,
            $tableBox->orientation
        );

        return $content;
    }

    public static function computeColumnBoxes($columns, $tableBox)
    {
        $columnCount = count($columns);
        $width = floatval($tableBox->width) / $columnCount;
        $x = floatval($tableBox->x);

        $boxes = [];
        foreach ($columns as $cn => $column) {
            $boxes[] = new RectInfo(
                $x + self::TABLE_MARGIN / 2.0,
                $tableBox->y - self::TABLE_MARGIN,
                $width - self::TABLE_MARGIN,
                $tableBox->height - self::TABLE_MARGIN,
                $tableBox->orientation
            );
            $x += $width;
        }

        return $boxes;
    }

    public static function computeCellBox($info, $box)
    {
        return $box;
    }
}
