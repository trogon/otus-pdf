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

use trogon\otuspdf\Page;
use trogon\otuspdf\base\ArgumentException;

class PageCollection extends \trogon\otuspdf\base\DependencyObject
    implements \ArrayAccess, \Countable, \IteratorAggregate
{
    private $container;

    public function init()
    {
        parent::init();
        $this->container = [];
    }

    /**
     * @param Page|array $config
     * @return Page
     */
    public function add($config = [])
    {
        if ($config instanceof Page) {
            $this->container[] = $config;
            return $config;
        } elseif (is_array($config)) {
            $page = new Page($config);
            $this->container[] = $page;
            return $page;
        } else {
            throw new ArgumentException("Invalid type. Page or array expected.");
        }
    }

    /**
     * @param Page $page
     * @return boolean
     */
    public function remove(Page $page)
    {
        $key = array_search($page, $this->container);
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
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
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
