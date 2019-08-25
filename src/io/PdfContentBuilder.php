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
use trogon\otuspdf\io\PdfContentState;

class PdfContentBuilder extends \trogon\otuspdf\base\DependencyObject
{
    const NONE_STATE = 0;
    const SCOPE_STATE = 1;
    const TEXT_STATE = 2;
    const TEXT_STATE_SCOPE = 3;
    const PATH_STATE = 4;
    const PATH_STATE_SCOPE = 5;
    const SHADING_STATE = 8;
    const EXTERNAL_STATE = 16;
    const IMAGE_STATE = 32;
    const ALL_STATE = 2147483647;

    /**
     * @var int
     */
    private $currentState;
    /**
     * @var PdfContentState
     */
    private $contentState;

    public function init()
    {
        parent::init();
        $this->currentState = self::NONE_STATE;
        $this->contentState = new PdfContentState();
    }

    /**
     * Defines begin point __(x, y)__ of the path segment.
     */
    public function beginPath($x, $y)
    {
        $this->verifyState(self::NONE_STATE,
            'BeginPath requires NONE state. Finish pending operations.');
        $this->setState(self::PATH_STATE);
        return "\t $x $y m\n";
    }

    public function beginText()
    {
        $this->verifyState(self::NONE_STATE,
            'BeginText requires NONE state. Finish pending operations.');
        $this->setState(self::TEXT_STATE);
        return "BT\n";
    }

    public function beginTextRender()
    {
        $this->verifyState(self::TEXT_STATE,
            'BeginTextRender requires TEXT state. Start new text block with BeginText.');
        $this->setState(self::TEXT_STATE_SCOPE);
        return "\t (";
    }

    public function checkState($expectedState)
    {
        if (is_array($expectedState)) {
            return in_array($this->currentState, $expectedState);
        } else {
            return $expectedState === null 
                || $this->currentState === $expectedState;
        }
    }

    /**
     * Draws bezier cubic curve path segment,
     * starting on last path point and ends at __(x, y)__
     * using one control point __(ex, ey)__ for end point.
     */
    public function drawBezierEndControl($x, $y, $ex, $ey)
    {
        $this->verifyState([self::PATH_STATE, self::PATH_STATE_SCOPE],
            'DrawBezier requires PATH state. Start new path with BeginPath.');
        $this->setState(self::PATH_STATE_SCOPE);
        return "\t $ex $ey $x $y v\n";
    }

    /**
     * Draws bezier cubic curve path segment,
     * starting on last path point and ends at __(x, y)__
     * using two control points __(bx, by)__ for start, and __(ex, ey)__ for end point.
     */
    public function drawBezierFullControl($x, $y, $bx, $by, $ex, $ey)
    {
        $this->verifyState([self::PATH_STATE, self::PATH_STATE_SCOPE],
            'DrawBezier requires PATH state. Start new path with BeginPath.');
        $this->setState(self::PATH_STATE_SCOPE);
        return "\t $bx $by $ex $ey $x $y c\n";
    }

    /**
     * Draws bezier cubic curve path segment,
     * starting on last path point and ends at __(x, y)__
     * using one control point __(bx, by)__ for start point.
     */
    public function drawBezierStartControl($x, $y, $bx, $by)
    {
        $this->verifyState([self::PATH_STATE, self::PATH_STATE_SCOPE],
            'DrawBezier requires PATH state. Start new path with BeginPath.');
        $this->setState(self::PATH_STATE_SCOPE);
        return "\t $bx $by $x $y y\n";
    }

    /**
     * Draws line path segment,
     * starting on last path point and ends at __(x, y)__.
     */
    public function drawLine($x, $y)
    {
        $this->verifyState([self::PATH_STATE, self::PATH_STATE_SCOPE],
            'DrawLine requires PATH state. Start new path with BeginPath.');
        $this->setState(self::PATH_STATE_SCOPE);
        return "\t $x $y l\n";
    }

    public function drawRectangle($x, $y, $width, $height, $fill = false)
    {
        $this->verifyState(self::NONE_STATE,
            'DrawRectangle requires NONE state. Finish pending operations.');
        $this->setState(self::PATH_STATE);
        return "\t $x $y $width $height re\n";
    }

    public function endPath($fill = false, $close = false)
    {
        $this->verifyState([self::PATH_STATE, self::PATH_STATE_SCOPE],
            'EndPath requires PATH state. No path is being constructed.');
        $this->setState(self::NONE_STATE);
        return "\t S\n";
    }

    public function endText()
    {
        $this->verifyState(self::TEXT_STATE,
            'EndText requires TEXT state. Start new text block with BeginText.');
        $this->setState(self::NONE_STATE);
        return "ET\n";
    }

    public function endTextRender()
    {
        $this->verifyState(self::TEXT_STATE_SCOPE,
            'EndTextRender requires TEXT scope. Start new text scope with BeginTextRender.');
        $this->setState(self::TEXT_STATE);
        return ") Tj\n";
    }

    public function newLine()
    {
        $this->verifyState(self::TEXT_STATE,
            'NewLine requires TEXT state. Start new text block with BeginText.');
        return "\t T*\n";
    }

    /**
     * Character spacing
     * @param $value Value in font unit
     */
    public function setCharacterSpacing($value)
    {
        $this->verifyState(self::TEXT_STATE,
            'SetCharacterSpacing requires TEXT state. Start new text block with BeginText.');
        $this->contentState->characterSpacing = $value;
        return "\t $value Tc\n";
    }

    /**
     * Horizontal scaling
     * @param $value Value procetage (100 = 100%)
     */
    public function setHorizontalScaling($value)
    {
        $this->verifyState(self::TEXT_STATE,
            'SetHorizontalScaling requires TEXT state. Start new text block with BeginText.');
        $this->contentState->horizontalScaling = $value;
        return "\t $value Tz\n";
    }

    public function setFont($fontKey, $fontSize)
    {
        return $this->setTextFont($fontKey, $fontSize);
    }

    public function setState($newState)
    {
        $this->currentState = $newState;
    }

    public function setStrokeColorRgb($r, $g, $b)
    {
        $this->verifyState(self::NONE_STATE,
            'SetStrokeColor requires NONE state. Finish pending operations.');
        $r /= 255.0;
        $g /= 255.0;
        $b /= 255.0;
        return "\t $r $g $b rg\n";
    }

    public function setTextColorRgb($r, $g, $b)
    {
        $this->verifyState(self::TEXT_STATE,
            'SetTextColor requires TEXT state. Start new text block with BeginText.');
        $r /= 255.0;
        $g /= 255.0;
        $b /= 255.0;
        return "\t $r $g $b rg\n";
    }

    /**
     * Text font and size
     * @param $fontKey Key defined in font dictionary
     * @param $fontSize Size in font unit
     */
    public function setTextFont($fontKey, $fontSize)
    {
        $this->verifyState(self::TEXT_STATE,
            'SetTextFont requires TEXT state. Start new text block with BeginText.');
        $this->contentState->textFont = $fontKey;
        $this->contentState->textFontSize = $fontSize;
        return "\t /$fontKey $fontSize Tf\n";
    }

    /**
     * Text leading
     * @param $value Value in font unit
     */
    public function setTextLeading($value)
    {
        $this->verifyState(self::TEXT_STATE,
            'SetTextLeading requires TEXT state. Start new text block with BeginText.');
        $this->contentState->textLeading = $value;
        return "\t $value TL\n";
    }

    public function setTextPosition($x, $y, $isRelative = true)
    {
        $this->verifyState(self::TEXT_STATE,
            'SetTextPosition requires TEXT state. Start new text block with BeginText.');
        if ($isRelative === true) {
            return "\t $x $y Td\n";
        } else {
            return "\t $x $y TD\n";
        }
    }

    /**
     * Text render mode
     * @param $value Value in font unit
     */
    public function setTextRenderingMode($value)
    {
        $this->verifyState(self::TEXT_STATE,
            'SetTextRenderingMode requires TEXT state. Start new text block with BeginText.');
        $this->contentState->textRenderingMode = $value;
        return "\t $value Tr\n";
    }

    /**
     * Text rise
     * @param $value Value in font unit
     */
    public function setTextRise($value)
    {
        $this->verifyState(self::TEXT_STATE,
            'SetTextRise requires TEXT state. Start new text block with BeginText.');
        $this->contentState->textRise = $value;
        return "\t $value Ts\n";
    }

    /**
     * Word spacing
     * @param $value Value in font unit
     */
    public function setWordSpacing($value)
    {
        $this->verifyState(self::TEXT_STATE,
            'SetWordSpacing requires TEXT state. Start new text block with BeginText.');
        $this->contentState->wordSpacing = $value;
        return "\t $value Tw\n";
    }

    public function verifyState($expectedState = null, $errorMessage = null)
    {
        if (!$this->checkState($expectedState)) {
            $message = "Can not begin state. Current state {$this->currentState}, expected {$expectedState}.";
            if (is_string($errorMessage)) {
                $message .= ' ' . $errorMessage;
            }
            throw new InvalidOperationException($message);
        }
        return true;
    }
}
