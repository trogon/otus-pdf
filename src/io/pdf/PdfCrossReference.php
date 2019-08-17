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
namespace trogon\otuspdf\io\pdf;

use trogon\otuspdf\io\pdf\PdfObject;

class PdfCrossReference extends \trogon\otuspdf\base\DependencyObject
{
    private $objects;
    private $objectLocations;
    private $refBlocks;

    public function init()
    {
        parent::init();
        $this->objects = [];
        $this->objectLocations = [];
        $this->refBlocks = [0];
    }

    public function getSize()
    {
        $keys = \array_keys($this->refBlocks);
        $lastKey = end($keys);
        return $lastKey + $this->refBlocks[$lastKey];
    }

    public function registerObject(PdfObject $pdfObject, $offset)
    {
        $this->objects[$pdfObject->id] = $pdfObject;
        $this->objectLocations[$pdfObject->id] = $offset;
        $this->computeRefBlocks();
    }

    private function computeRefBlocks()
    {
        $blocks = [];
        $blockSize = 0;
        $currentBlock = 0;
        $currentObjectId = -1;
        $keys = array_keys($this->objects);
        sort($keys);
        foreach ($keys as $id) {
            if ($id != $currentObjectId) {
                $blocks[$currentBlock] = $blockSize;
                $blockSize = 1;
                $currentObjectId = $id;
                $currentBlock = $id;
            } else {
                $blockSize++;
            }
            $currentObjectId++;
        }
        $blocks[$currentBlock] = $blockSize;
        $this->refBlocks = $blocks;
    }

    public function toString()
    {
        $content = "xref";
        $currentObjectId = -1;
        $keys = array_keys($this->objects);
        sort($keys);
        foreach ($keys as $id) {
            $pdfObject = $this->objects[$id];
            if ($id != $currentObjectId) {
                $currentObjectId = $id;
                $content .= "\n{$id} {$this->refBlocks[$id]}";
            }
            if ($pdfObject instanceof PdfObject && !$pdfObject->isNull) {
                $offset = $this->objectLocations[$id];
                $version = $pdfObject->version;
                $content .= "\n" . substr('0000000000' . $offset, -10) . ' ' . substr('00000' . $version, -5) . " n ";
            } else {
                $content .= "\n0000000000 65535 f ";
            }
            $currentObjectId++;
        }
        return $content;
    }
}
