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
namespace trogon\otuspdf;

use trogon\otuspdf\meta\PageInfo;
use trogon\otuspdf\PageBreak;
use trogon\otuspdf\Paragraph;
use trogon\otuspdf\TextBlock;

 /**
  * @property-read BlockCollection $blocks
  * @property-read PageInfo $info
  */
class Page extends \trogon\otuspdf\base\ContentElement
{
    private $blocks;

    public function init()
    {
        parent::init();
        $this->blocks = new BlockCollection();
    }

    protected function createInfo($config)
    {
        return new PageInfo($config);
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    public function addPagebreak($config = [])
    {
        return $this->blocks[] = new PageBreak($config);
    }

    public function addParagraph($config = [])
    {
        return $this->blocks[] = new Paragraph($config);
    }

    public function addText($text, $config = [])
    {
        return $this->blocks[] = new TextBlock($text, $config);
    }
}
