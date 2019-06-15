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
use trogon\otuspdf\io\pdf\PdfDictionary;
use trogon\otuspdf\io\pdf\PdfName;
use trogon\otuspdf\io\pdf\PdfNumber;
use trogon\otuspdf\io\pdf\PdfObject;
use trogon\otuspdf\io\pdf\PdfObjectReference;
use trogon\otuspdf\io\pdf\PdfStream;
use trogon\otuspdf\meta\PageOrientationInfo;
use trogon\otuspdf\meta\PageSizeInfo;

class PageRender extends \trogon\otuspdf\base\BaseObject
{
    private $objectFactory;
    private $resourcesDict;
    private $defaultOrientation;
    private $defaultSize;
    private $pageCollectionObj;
    private $pageRefs;

    public function __construct($objectFactory, $resourcesDict)
    {
        $this->objectFactory = $objectFactory;
        $this->resourcesDict = $resourcesDict;
        $this->defaultOrientation = PageOrientationInfo::getPortrait();
        $this->defaultSize = PageSizeInfo::getA4();
    }

    public function renderPageCollection($pageCollection, $catalogObj)
    {
        $defaultArraySize = $this->createArraySize($this->defaultOrientation, $this->defaultSize);

        $pageCollectionObj = $this->objectFactory->create();
        $pageCollectionObj->content = new PdfDictionary();
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Pages'])
        );
        $this->pageRefs = new PdfArray();
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Kids']),
            $this->pageRefs
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Count']),
            new PdfNumber(['value' => \count($pageCollection)])
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Resources']),
            $this->resourcesDict
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'MediaBox']),
            $defaultArraySize
        );
        $catalogObj->content->addItem(
            new PdfName(['value' => 'Pages']),
            new PdfObjectReference(['object' => $pageCollectionObj])
        );

        $this->pageCollectionObj = $pageCollectionObj;
        return $pageCollectionObj;
    }

    public function renderPage($page)
    {
        $objects = [];

        // PDF Page <n>
        $pageObj = $this->objectFactory->create();
        $pageObj->content = new PdfDictionary();
        $pageObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Page'])
        );
        $pageObj->content->addItem(
            new PdfName(['value' => 'Parent']),
            new PdfObjectReference(['object' => $this->pageCollectionObj])
        );
        list($orientation, $size, $isDefined) = $this->findOrientationAndSize($page->info, $this->defaultOrientation, $this->defaultSize);
        if ($isDefined) {
            $arraySize = $this->createArraySize($orientation, $size);
            $pageObj->content->addItem(
                new PdfName(['value' => 'MediaBox']),
                $arraySize
            );
        }
        $objects[] = $pageObj;
        $this->pageRefs->addItem(new PdfObjectReference(['object' => $pageObj]));

        // PDF Page <n> content
        $pageContentObj = $this->objectFactory->create();
        $pageContentObj->content = new PdfDictionary();
        $this->writeContentStream($page, $pageContentObj, $orientation, $size);
        $objects[] = $pageContentObj;
        $pageObj->content->addItem(
            new PdfName(['value' => 'Contents']),
            new PdfObjectReference(['object' => $pageContentObj])
        );

        return $objects;
    }

    public function createArraySize($orientation, $size)
    {
        $pageSizeArray = new PdfArray();
        $pageSizeArray->addItem(new PdfNumber(['value' => (0 * 72)]));
        $pageSizeArray->addItem(new PdfNumber(['value' => (0 * 72)]));
        if ($orientation->isLandscape()) {
            $pageSizeArray->addItem(new PdfNumber(['value' => ($size->width * 72)]));
            $pageSizeArray->addItem(new PdfNumber(['value' => ($size->height * 72)]));
        } else {
            $pageSizeArray->addItem(new PdfNumber(['value' => ($size->height * 72)]));
            $pageSizeArray->addItem(new PdfNumber(['value' => ($size->width * 72)]));
        }
        return $pageSizeArray;
    }

    public function findOrientationAndSize($pageInfo, $defautOrientation, $defaultSize)
    {
        $orientation = $pageInfo->orientation;
        $size = $pageInfo->size;

        $isOrientationDefined = ($orientation instanceof PageOrientationInfo);
        $isSizeDefined = ($size instanceof PageSizeInfo);

        $isDefined = ($isOrientationDefined && $orientation != $defautOrientation)
        || ($size && $orientation != $defaultSize);

        $orientation = $isOrientationDefined ? $orientation: $defautOrientation;
        $size = $isSizeDefined ? $size : $defaultSize;

        return [$orientation, $size, $isDefined];
    }

    private function writeContentStream($page, $pageContentObj, $pageOrientation, $pageSize)
    {
        $textRender = new TextRender();

        $content = $textRender->renderTextItems($page->items, $pageOrientation, $pageSize);

        $contentStream = new PdfStream();
        $contentStream->value = \gzcompress($content);
        $pageContentObj->stream = $contentStream;
        $pageContentObj->content->addItem(
            new PdfName(['value' => 'Filter']),
            new PdfName(['value' => 'FlateDecode'])
        );
        $pageContentObj->content->addItem(
            new PdfName(['value' => 'Length']),
            new PdfNumber(['value' => $contentStream->length])
        );
    }
}
