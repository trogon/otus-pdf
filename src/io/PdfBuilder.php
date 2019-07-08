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

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\pdf\PdfArray;
use trogon\otuspdf\io\pdf\PdfCrossReference;
use trogon\otuspdf\io\pdf\PdfDictionary;
use trogon\otuspdf\io\pdf\PdfName;
use trogon\otuspdf\io\pdf\PdfNumber;
use trogon\otuspdf\io\pdf\PdfObject;
use trogon\otuspdf\io\pdf\PdfObjectFactory;
use trogon\otuspdf\io\pdf\PdfObjectReference;
use trogon\otuspdf\io\pdf\PdfStream;
use trogon\otuspdf\io\pdf\PdfString;
use trogon\otuspdf\io\pdf\PdfTrailer;

class PdfBuilder extends \trogon\otuspdf\base\BaseObject
{
    private $objectFactory;

    public function __construct()
    {
        $this->objectFactory = new PdfObjectFactory();
    }

    public function getObjectFactory()
    {
        return $this->objectFactory;
    }

    public function createBasicFont($name, $family)
    {
        $fontObj = $this->objectFactory->create();
        $fontObj->content = new PdfDictionary();
        $fontObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Font'])
        );
        $fontObj->content->addItem(
            new PdfName(['value' => 'Subtype']),
            new PdfName(['value' => 'Type1'])
        );
        $fontObj->content->addItem(
            new PdfName(['value' => 'Name']),
            new PdfName(['value' => $name])
        );
        $fontObj->content->addItem(
            new PdfName(['value' => 'BaseFont']),
            new PdfName(['value' => $family])
        );
        $fontObj->content->addItem(
            new PdfName(['value' => 'Encoding']),
            new PdfName(['value' => 'WinAnsiEncoding'])
        );
        return $fontObj;
    }

    public function createCatalog()
    {
        $catalogObj = $this->objectFactory->create();
        $catalogObj->content = new PdfDictionary();
        $catalogObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Catalog'])
        );
        return $catalogObj;
    }

    public function createCrossReference()
    {
        return new PdfCrossReference();
    }

    public function createDocumentInfo($config = [])
    {
        $dictionary = new PdfDictionary();
        $isEmpty = true;
        foreach ($config as $key => $value) {
            if (!empty($value)) {
                $dictionary->addItem(
                    new PdfName(['value' => $key]),
                    new PdfString(['value' => $value])
                );
                $isEmpty = false;
            }
        }
        if (!$isEmpty) {
            $documentInfoObj = $this->objectFactory->create();
            $documentInfoObj->content = $dictionary;
            return $documentInfoObj;
        }
        return null;
    }

    public function createFontsResource()
    {
        return new PdfDictionary();
    }

    public function createOutlines()
    {
        $outlinesObj = $this->objectFactory->create();
        $outlinesObj->content = new PdfDictionary();
        $outlinesObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Outlines'])
        );
        $outlinesObj->content->addItem(
            new PdfName(['value' => 'Count']),
            new PdfNumber(['value' => 0])
        );
        return $outlinesObj;
    }

    public function createProcSet()
    {
        $procSetObj = $this->objectFactory->create();
        $procSetObj->content = new PdfArray();
        $procSetObj->content->addItem(new PdfName(['value' => 'PDF']));
        return $procSetObj;
    }

    public function createResourceCatalog()
    {
        return  new PdfDictionary();
    }

    public function createTrailer($rootObject, $xrefOffset, $xrefSize, $documentInfoObject = null)
    {
        $trailer = new PdfTrailer([
            'xrefOffset' => $xrefOffset
        ]);
        $trailer->content->addItem(
            new PdfName(['value' => 'Size']),
            new PdfNumber(['value' => $xrefSize])
        );
        $trailer->content->addItem(
            new PdfName(['value' => 'Root']),
            new PdfObjectReference(['object' => $rootObject])
        );
        if ($documentInfoObject instanceof PdfObject) {
            $trailer->content->addItem(
                new PdfName(['value' => 'Info']),
                new PdfObjectReference(['object' => $documentInfoObject])
            );
        }
        return $trailer;
    }

    public function registerFont($fontsDict, $fontObj)
    {
        $fontsDict->addItem(
            new PdfName(['value' => $fontObj->content->getItem('Name')->value]),
            new PdfObjectReference(['object' => $fontObj])
        );
    }

    public function registerFontsResource($resourcesDict, $fontsDict)
    {
        $resourcesDict->addItem(
            new PdfName(['value' => 'Font']),
            $fontsDict
        );
    }

    public function registerOutlines($catalogObj, $outlinesObj)
    {
        $catalogObj->content->addItem(
            new PdfName(['value' => 'Outlines']),
            new PdfObjectReference(['object' => $outlinesObj])
        );
    }

    public function registerProcSetResource($resourcesDict, $procSetObj)
    {
        $resourcesDict->addItem(
            new PdfName(['value' => 'ProcSet']),
            new PdfObjectReference(['object' => $procSetObj])
        );
    }
}
