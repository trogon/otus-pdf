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

/**
 * @property-read TableColumnCollection $columns
 * @property-read TableInfo $info
 * @property-read TableRowGroupCollection $rowGroups
 */
class Table extends \trogon\otuspdf\Block
{
    /**
     * @var TableColumnCollection
     */
    private $columns;
    /**
     * @var TableRowGroupCollection
     */
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

    /**
     * @return TableColumnCollection
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return TableRowGroupCollection
     */
    public function getRowGroups()
    {
        return $this->rowGroups;
    }

    /**
     * @param array $config
     * @return TableColumn
     */
    public function addColumn($config = [])
    {
        return $this->columns[] = new TableColumn($config);
    }

    /**
     * @param array $config
     * @return TableRowGroup
     */
    public function addRowGroup($config = [])
    {
        return $this->rowGroups[] = new TableRowGroup($config);
    }
}
