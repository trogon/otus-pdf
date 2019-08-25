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

class TextRender extends \trogon\otuspdf\base\DependencyObject
{
    const LINE_SEPARATOR = "\n";
    const REGEX_SPLIT_CHARS = '\X\K(?!$)';
    const REGEX_SPLIT_WORDS = '(?:(?<word>[\w\.\,]+)(?<separator>\W*))|(?<separator>\W+)';

    private $lineNumber;
    private $lineWidth;
    private $maxWidth;

    public function init()
    {
        parent::init();
        $this->lineNumber = 0;
        $this->lineWidth = 0;
    }

    /**
     * @param integer $charCode
     * @param double $fontSize
     * @param array $fontData
     * @return integer|float
     */
    public static function characterWidth($charCode, $fontSize, $fontData)
    {
        if (array_key_exists($charCode, $fontData->metrics)) {
            return ($fontData->metrics[$charCode]) * $fontSize / 1000;
        } else {
            return 72 / $fontSize;
        }
    }

    /**
     * @return integer
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * @return integer
     */
    public function getLineWidth()
    {
        return $this->lineWidth;
    }

    /**
     * @return integer
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * @param double $maxWidth
     */
    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;
    }

    /**
     * @param string $text
     * @param double $fontSize
     * @param array $fontData
     * @return integer|float
     */
    public static function textWidth($text, $fontSize, $fontData)
    {
        $chars = mb_split(self::REGEX_SPLIT_CHARS, $text);
        $width = 0;
        foreach ($chars as $char) {
            $charCode = self::mb_ord($char); // Method available since PHP 7.0 :(
            $width += self::characterWidth($charCode, $fontSize, $fontData);
        }
        return $width;
    }

    /**
     * @param string $text
     * @param double $fontSize
     * @param array $fontData
     * @return Generator<integer, string>
     */
    public function wrapText($text, $fontSize, $fontData)
    {
        $lineWidth = $this->lineWidth;
        $content = '';
        $previousSeparator = false;
        $previousSeparatorWidth = 0;
        if (mb_ereg_search_init($text, self::REGEX_SPLIT_WORDS)) {
            while (($regs = mb_ereg_search_regs()) !== false) {
                $word = $regs['word'];
                $separator = $regs['separator'];
                if (!empty($word)) {
                    $wordWidth = self::textWidth($word, $fontSize, $fontData);
                    if (!empty($previousSeparator)) {
                        $separatorParts = mb_split(self::LINE_SEPARATOR, $previousSeparator);
                        if (count($separatorParts) === 1) {
                            $lineWidth += $previousSeparatorWidth;
                            $content .= $previousSeparator;
                        } else {
                            $firstPart = array_shift($separatorParts);
                            $lastPart = array_pop($separatorParts);
                            $content .= $firstPart;
                            yield $content;
                            foreach ($separatorParts as $separatorPart) {
                                yield $separatorPart;
                            }
                            $lineWidth = self::textWidth($lastPart, $fontSize, $fontData);
                            $content = $lastPart;
                        }
                    }
                    $lineWidth += $wordWidth;
                    if ($lineWidth > $this->maxWidth) {
                        if ($wordWidth <= $this->maxWidth) {
                            $this->lineNumber++;
                            yield $content;
                            $this->lineWidth = $lineWidth - $wordWidth;
                            $lineWidth = $wordWidth;
                            $content = $word;
                        } else {
                            foreach($this->splitWord($word, $this->maxWidth, $fontSize, $fontData) as $subwidth => $subword) {
                                $this->lineNumber++;
                                yield $content;
                                $this->lineWidth = $lineWidth - $wordWidth;
                                $lineWidth = $subwidth;
                                $content = $subword;
                            }
                        }
                    } else {
                        $content .= $word;
                    }
                }
                if (!empty($separator)) {
                    $separatorWidth = self::textWidth($separator, $fontSize, $fontData);
                } else {
                    $separatorWidth = 0;
                }
                $previousSeparator = $separator;
                $previousSeparatorWidth = $separatorWidth;
            }
        }

        if (!empty($content)) {
            $this->lineWidth = $lineWidth;
            $this->lineNumber++;
            yield $content;
        }
    }

    public function splitWord($word, $maxWidth, $fontSize, $fontData)
    {
        $chars = mb_split(self::REGEX_SPLIT_CHARS, $word);
        $width = 0;
        $subword = '';
        foreach ($chars as $char) {
            $charCode = self::mb_ord($char); // Method available since PHP 7.0 :(
            $charWidth = self::characterWidth($charCode, $fontSize, $fontData);
            $width += $charWidth;
            if ($width > $maxWidth) {
                yield ($width - $charWidth) => $subword;
                $width = $charWidth;
                $subword = '';
            }
            $subword .= $char;
        }
        if ($width > 0) {
            yield $width => $subword;
        }
    }

    /**
     * @param string $char
     * @return integer
     */
    public static function mb_ord($char)
    {
        $charCode = 0;
        $bytes = str_split($char);
        foreach ($bytes as $byte) {
            $charCode = ($charCode << 8) + ord($byte);
        }
        return $charCode;
    }
}
