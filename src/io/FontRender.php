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
use trogon\otuspdf\io\PdfBuilder;
use trogon\otuspdf\meta\FontFamilyInfo;

class FontRender extends \trogon\otuspdf\base\DependencyObject
{
    private $fontData;
    private $fontKeys;
    private $fontsCatalog;
    private $builder;
    
    public function __construct(PdfBuilder $builder)
    {
        $this->fontsCatalog = $builder->createFontsResource();
        $this->builder = $builder;
        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $this->fontKeys = [];
    }

    public function createFontObjects()
    {
        foreach ($this->fontKeys as $fontFamily => $fontKey) {
            $fontObject = $this->builder->createBasicFont($fontKey, $fontFamily);
            $this->builder->registerFont($this->fontsCatalog, $fontObject);
            yield $fontObject;
        }
    }

    public function findFontKey($fontFamily)
    {
        if (array_key_exists($fontFamily, $this->fontKeys)) {
            return $this->fontKeys[$fontFamily];
        } else {
            $newKey = $this->createFontKey($fontFamily);
            $this->fontKeys[$fontFamily] = $newKey;
            $this->fontData[$newKey] = $this->loadFontData($fontFamily);
            return $newKey;
        }
        return null;
    }

    public function findFontData($fontKey)
    {
        if (array_key_exists($fontKey, $this->fontData)) {
            return $this->fontData[$fontKey];
        } else {
            return null;
        }
    }

    public function registerFontsResource($resourcesDict)
    {
        $this->builder->setFontsResource($resourcesDict, $this->fontsCatalog);
    }

    private function createFontKey($fontFamily)
    {
        return md5($fontFamily) . count($this->fontKeys);
    }

    private function loadFontData($fontFamily)
    {
        $dataPath = FontInstaller::getDataPath();
        $filePath = join(DIRECTORY_SEPARATOR, [$dataPath, $fontFamily . '.php']);
        if (is_file($filePath)) {
            $fontData = file_get_contents($filePath);
            $fontInfo = unserialize($fontData);
            return $fontInfo;
        } else {
            return new FontFamilyInfo([
                'name' => $fontFamily
            ]);
        }
    }
}
