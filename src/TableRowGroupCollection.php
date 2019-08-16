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

use ArrayIterator;

use trogon\otuspdf\TableRowGroup;
use trogon\otuspdf\base\InvalidCallException;

class TableRowGroupCollection extends \trogon\otuspdf\base\DependencyObject
    implements \ArrayAccess, \Countable, \IteratorAggregate
{
    private $container;

    public function init()
    {
        parent::init();
        $this->container = [];
    }

    public function add(TableRowGroup $item)
    {
        $this->container[] = $item;
        return $item;
    }

    public function contains(TableRowGroup $item)
    {
        $key = array_search($item, $this->container, true);
        if ($key !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function remove(TableRowGroup $item)
    {
        $key = array_search($item, $this->container, true);
        if ($key !== false) {
            unset($this->container[$key]);
            return true;
        } else {
            return false;
        }
    }

    /* ArrayAccess Methods */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if ($value instanceof TableRowGroup) {
            if (is_null($offset)) {
                $this->container[] = $value;
            } else {
                $this->container[$offset] = $value;
            }
        } else {
            throw new InvalidCallException("Only TableRowGroup type elements can be set");
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /* Countable Methods */
    public function count()
    {
        return count($this->container);
    }

    /* IteratorAggregate Methods */
    public function getIterator()
    {
        return new ArrayIterator($this->container);
    }
}
