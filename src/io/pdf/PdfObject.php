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
namespace insma\otuspdf\io\pdf;

class PdfObject extends \insma\otuspdf\base\BaseObject
{
    private $id;
    private $version;
    private $content;
    private $streamObject;

    public function __construct($id, $version = 0)
    {
        $this->id = $id;
        $this->version = $version;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getStream()
    {
        return $this->streamObject;
    }

    public function setStream($stream)
    {
        $this->streamObject = $stream;
    }

    public function toString()
    {
        $content = "{$this->id} {$this->version} obj\n";
        $content .= $this->content->toString() . "\n";
        if (!empty($this->streamObject)) {
            $content .= $this->streamObject->toString() . "\n";
        }
        $content .= "endobj";
        return $content;
    }
}
