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
namespace trogon\otuspdf\base;

use trogon\otuspdf\base\NotImplementedException;

abstract class ContentElement extends DependencyObject
{
    protected $info;
    
    public function __construct($config = [])
    {
        $this->info = $this->createInfo($config);
        parent::__construct();
    }

    protected function createInfo($config)
    {
        throw new NotImplementedException('Method createInfo not implemented');
    }

    public function getInfo()
    {
        return $this->info;
    }
}
