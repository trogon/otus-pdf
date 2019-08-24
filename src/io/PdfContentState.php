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

class PdfContentState extends \trogon\otuspdf\base\DependencyObject
{
    const TRM_FILL = 0;
    const TRM_STROKE = 1;
    const TRM_FILL_AND_STROKE = 2;
    const TRM_INVISIBLE = 3;
    const TRM_FILL_CLIPPING = 4;
    const TRM_STROKE_CLIPPING = 5;
    const TRM_FILL_AND_STROKE_CLIPPING = 6;
    const TRM_CLIPPING = 7;

    /**
     * Set the character spacing, Tc, to charSpace, which shall be a number 
     * expressed in unscaled text space units. Character spacing shall be used 
     * by the Tj, TJ, and ' operators. Initial value: 0.
     * @var float
     */
    public $characterSpacing = 0;
    /**
     * Set the word spacing, Tw, to wordSpace, which shall be a number 
     * expressed in unscaled text space units. Word spacing shall be used by 
     * the Tj, TJ, and ' operators. Initial value: 0.
     * @var float
     */
    public $wordSpacing = 0;
    /**
     * Set the horizontal scaling, Th, to (scale ÷ 100). scale shall be a number 
     * specifying the percentage of the normal width. Initial value: 100 (normal 
     * width).
     * @var float
     */
    public $horizontalScaling = 100;
    /**
     * Set the text leading, Tl, to leading, which shall be a number expressed in 
     * unscaled text space units. Text leading shall be used only by the T*, ', and 
     * " operators. Initial value: 0.
     * @var float
     */
    public $textLeading = 0;
    /**
     * @var string
     */
    public $textFont;
    /**
     * Set the text font, Tf, to font and the text font size, Tfs, to size. font shall be 
     * the name of a font resource in the Font subdictionary of the current 
     * resource dictionary; size shall be a number representing a scale factor. 
     * There is no initial value for either font or size; they shall be specified 
     * explicitly by using Tf before any text is shown.
     * @var float
     */
    public $textFontSize;
    /**
     * Set the text rendering mode, Tmode, to render, which shall be an integer. 
     * Initial value: 0.
     * @var float
     */
    public $textRenderingMode = 0;
    /**
     * Set the text rise, Trise, to rise, which shall be a number expressed in 
     * unscaled text space units. Initial value: 0.
     * @var float
     */
    public $textRise = 0;
}
