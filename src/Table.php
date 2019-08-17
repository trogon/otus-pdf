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

use trogon\otuspdf\meta\TableInfo;
use trogon\otuspdf\TableColumnCollection;
use trogon\otuspdf\TableRowGroupCollection;

class Table extends \trogon\otuspdf\Block
{
    private $columns;
    private $rowGroups;

    public function init()
    {
        parent::init();
        $this->columns = new TableColumnCollection();
        $this->rowGroups = new TableRowGroupCollection();
    }

    protected function createInfo($config)
    {
        return new TableInfo($config);
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getRowGroups()
    {
        return $this->rowGroups;
    }

    public function addColumn($config = [])
    {
        return $this->columns[] = new TableColumn($config);
    }

    public function addRowGroup($config = [])
    {
        return $this->rowGroups[] = new TableRowGroup($config);
    }
}