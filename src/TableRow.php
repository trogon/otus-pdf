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

use trogon\otuspdf\TableCellCollection;

/**
 * @property-read TableCellCollection $cells
 */
class TableRow extends \trogon\otuspdf\TextElement
{
    /**
     * @var TableCellCollection
     */
    private $cells;

    public function init()
    {
        parent::init();
        $this->cells = new TableCellCollection();
    }

    /**
     * @return TableCellCollection
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * @param array $config
     * @return TableCell
     */
    public function addCell($config = [])
    {
        return $this->cells[] = new TableCell($config);
    }
}
