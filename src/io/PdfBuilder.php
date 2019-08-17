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
use trogon\otuspdf\io\pdf\PdfNull;
use trogon\otuspdf\io\pdf\PdfNumber;
use trogon\otuspdf\io\pdf\PdfObject;
use trogon\otuspdf\io\pdf\PdfObjectFactory;
use trogon\otuspdf\io\pdf\PdfObjectReference;
use trogon\otuspdf\io\pdf\PdfStream;
use trogon\otuspdf\io\pdf\PdfString;
use trogon\otuspdf\io\pdf\PdfTrailer;

class PdfBuilder extends \trogon\otuspdf\base\DependencyObject
{
    private $objectFactory;

    public function init()
    {
        parent::init();
        $this->objectFactory = new PdfObjectFactory();
    }

    /**
     * @return PdfObjectFactory
     */
    public function getObjectFactory()
    {
        return $this->objectFactory;
    }

    /**
     * @return PdfObject
     */
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

    /**
     * @return PdfObject
     */
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

    /**
     * @return PdfCrossReference
     */
    public function createCrossReference()
    {
        return new PdfCrossReference();
    }

    /**
     * @return PdfDictionary
     */
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

    /**
     * @return PdfDictionary
     */
    public function createFontsResource()
    {
        return new PdfDictionary();
    }

    /**
     * @return PdfObject
     */
    public function createExternalGraphicState()
    {
        $extGStateObj = $this->objectFactory->create();
        $extGStateObj->content = new PdfDictionary();
        $extGStateObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'ExtGState'])
        );
        return $extGStateObj;
    }

    /**
     * @return PdfDictionary
     */
    public function createExternalGraphicStatesResource()
    {
        return new PdfDictionary();
    }

    /**
     * @return PdfArray
     */
    public function createMediaBox($width, $height, $x = 0, $y = 0)
    {
        $mediaBox = new PdfArray();
        $mediaBox->addItem(new PdfNumber(['value' => $x]));
        $mediaBox->addItem(new PdfNumber(['value' => $y]));
        $mediaBox->addItem(new PdfNumber(['value' => $width]));
        $mediaBox->addItem(new PdfNumber(['value' => $height]));
        return $mediaBox;
    }

    /**
     * @return PdfObject
     */
    public function createNullObject()
    {
        $nullObj = $this->objectFactory->create();
        $nullObj->content = new PdfNull();
        return $nullObj;
    }

    /**
     * @return PdfObject
     */
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

    /**
     * @return PdfObject
     */
    public function createPage(PdfObject $pageCollectionObj)
    {
        $pageObj = $this->objectFactory->create();
        $pageObj->content = new PdfDictionary();
        $pageObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Page'])
        );
        $pageObj->content->addItem(
            new PdfName(['value' => 'Parent']),
            new PdfObjectReference(['object' => $pageCollectionObj])
        );
        return $pageObj;
    }

    /**
     * @return PdfObject
     */
    public function createPageCollection($resourcesDict, $mediaBox)
    {
        $pageCollectionObj = $this->objectFactory->create();
        $pageCollectionObj->content = new PdfDictionary();
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Pages'])
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Kids']),
            new PdfArray()
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Count']),
            new PdfNumber(['value' => 0])
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Resources']),
            $resourcesDict
        );
        self::setCropBox($pageCollectionObj, $mediaBox);
        self::setMediaBox($pageCollectionObj, $mediaBox);
        return $pageCollectionObj;
    }

    /**
     * @return PdfObject
     */
    public function createPageContent()
    {
        $pageContentObj = $this->objectFactory->create();
        $pageContentObj->content = new PdfDictionary();
        return $pageContentObj;
    }

    /**
     * @return PdfObject
     */
    public function createProcSet()
    {
        $procSetObj = $this->objectFactory->create();
        $procSetObj->content = new PdfArray();
        $procSetObj->content->addItem(new PdfName(['value' => 'PDF']));
        $procSetObj->content->addItem(new PdfName(['value' => 'Text']));
        return $procSetObj;
    }

    /**
     * @return PdfDictionary
     */
    public function createResourceCatalog()
    {
        return  new PdfDictionary();
    }

    /**
     * @return PdfStream
     */
    public static function createStreamContent($streamContent)
    {
        $stream = new PdfStream();
        $stream->value = $streamContent;
        return $stream;
    }

    /**
     * @return PdfTrailer
     */
    public function createTrailer($rootObject, $xrefOffset, $xrefSize, $documentInfoObject = null, $idHex = null)
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
        if (!empty($idHex)) {
            $idArray = new PdfArray();
            $idArray->addItem(new PdfString([
                'value' => $idHex,
                'type' => PdfString::TYPE_HEX
            ]));
            $idArray->addItem(new PdfString([
                'value' => $idHex,
                'type' => PdfString::TYPE_HEX
            ]));
            $trailer->content->addItem(
                new PdfName(['value' => 'ID']),
                $idArray
            );
        }
        return $trailer;
    }

    /**
     * @return void
     */
    public static function registerCropBox(PdfObject $page, PdfObject $cropBox)
    {
        $page->content->addItem(
            new PdfName(['value' => 'CropBox']),
            new PdfObjectReference(['object' => $cropBox])
        );
    }

    /**
     * @return void
     */
    public static function registerFont(PdfDictionary $fonts, PdfObject $font, $alias = null)
    {
        if ($alias === null) {
            $alias = $font->content->getItem('Name')->value;
        }
        $fonts->addItem(
            new PdfName(['value' => $alias]),
            new PdfObjectReference(['object' => $font])
        );
    }

    /**
     * @return void
     */
    public static function registerExternalGraphicState(
        PdfDictionary $extGStates,
        PdfObject $extGState,
        $alias)
    {
        $extGStates->addItem(
            new PdfName(['value' => $alias]),
            new PdfObjectReference(['object' => $extGState])
        );
    }

    /**
     * @return void
     */
    public static function registerExternalGraphicStatesResource(
        PdfDictionary $resources,
        PdfObject $extGStates)
    {
        $resources->addItem(
            new PdfName(['value' => 'ExtGState']),
            new PdfObjectReference(['object' => $extGStates])
        );
    }

    /**
     * @return void
     */
    public static function registerMetadata(PdfObject $catalog, PdfObject $metadata)
    {
        $catalog->content->addItem(
            new PdfName(['value' => 'Metadata']),
            new PdfObjectReference(['object' => $metadata])
        );
    }

    /**
     * @return void
     */
    public static function registerMediaBox(PdfObject $page, PdfObject $mediaBox)
    {
        $page->content->addItem(
            new PdfName(['value' => 'MediaBox']),
            new PdfObjectReference(['object' => $mediaBox])
        );
    }

    /**
     * @return void
     */
    public static function registerOutlines(PdfObject $catalog, PdfObject $outlines)
    {
        $catalog->content->addItem(
            new PdfName(['value' => 'Outlines']),
            new PdfObjectReference(['object' => $outlines])
        );
    }

    /**
     * @return void
     */
    public static function registerPage(PdfObject $pageCollection, PdfObject $page)
    {
        $pageCollection->content->getItem('Kids')->addItem(
            new PdfObjectReference(['object' => $page])
        );
        $pageCollection->content->getItem('Count')->value++;
    }

    /**
     * @return void
     */
    public static function registerPageCollection(PdfObject $catalog, PdfObject $pageCollection)
    {
        $catalog->content->addItem(
            new PdfName(['value' => 'Pages']),
            new PdfObjectReference(['object' => $pageCollection])
        );
    }

    /**
     * @return void
     */
    public static function registerPageContent(PdfObject $page, PdfObject $pageContent)
    {
        $page->content->addItem(
            new PdfName(['value' => 'Contents']),
            new PdfObjectReference(['object' => $pageContent])
        );
    }

    /**
     * @return void
     */
    public static function registerProcSetResource(PdfDictionary $resources, PdfObject $procSet)
    {
        $resources->addItem(
            new PdfName(['value' => 'ProcSet']),
            new PdfObjectReference(['object' => $procSet])
        );
    }

    /**
     * @return void
     */
    public static function setCropBox(PdfObject $page, PdfArray $cropBox)
    {
        $page->content->addItem(
            new PdfName(['value' => 'CropBox']),
            $cropBox
        );
    }

    /**
     * @return void
     */
    public static function setExternalGraphicStatesResource(
        PdfDictionary $resources,
        PdfDictionary $extGStates)
    {
        $resources->addItem(
            new PdfName(['value' => 'ExtGState']),
            $extGStates
        );
    }

    /**
     * @return void
     */
    public static function setFontsResource(PdfDictionary $resources, PdfDictionary $fonts)
    {
        $resources->addItem(
            new PdfName(['value' => 'Font']),
            $fonts
        );
    }

    /**
     * @return void
     */
    public static function setMediaBox(PdfObject $page, PdfArray $mediaBox)
    {
        $page->content->addItem(
            new PdfName(['value' => 'MediaBox']),
            $mediaBox
        );
    }

    /**
     * @return void
     */
    public static function setStream(PdfObject $object, PdfStream $stream)
    {
        $object->stream = $stream;
        $object->content->addItem(
            new PdfName(['value' => 'Filter']),
            new PdfName(['value' => 'FlateDecode'])
        );
        $object->content->addItem(
            new PdfName(['value' => 'Length']),
            new PdfNumber(['value' => $stream->length])
        );
    }
}
