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
use trogon\otuspdf\meta\PaddingInfo;
use trogon\otuspdf\meta\PageInfo;
use trogon\otuspdf\meta\PageOrientationInfo;
use trogon\otuspdf\meta\PageSizeInfo;

class PageRender extends \trogon\otuspdf\base\BaseObject
{
    private $defaultPageInfo;
    private $pageCollectionObj;
    private $pdfBuilder;
    private $resourcesDict;

    public function __construct($pdfBuilder, $resourcesDict)
    {
        $this->pdfBuilder = $pdfBuilder;
        $this->resourcesDict = $resourcesDict;
        $this->defaultPageInfo = new PageInfo([
            'orientation' => PageOrientationInfo::getPortrait(),
            'size' => PageSizeInfo::getA4(),
            'margin' => PaddingInfo::pageNormal(),
        ]);
    }

    public function getPageInfo($page)
    {
        $mergedConfig = array_merge(
            $this->defaultPageInfo->toDictionary(),
            array_filter($page->info->toDictionary())
        );
        $mergedPageInfo = new PageInfo($mergedConfig);
        return $mergedPageInfo;
    }

    public function renderPageCollection($pageCollection, $catalogObj)
    {
        $defaultArraySize = $this->createArraySize($this->defaultPageInfo);

        $this->pageCollectionObj = 
            $this->pdfBuilder->createPageCollection($this->resourcesDict, $defaultArraySize);
        $this->pdfBuilder->registerPageCollection($catalogObj, $this->pageCollectionObj);
        return $this->pageCollectionObj;
    }

    public function renderPage($pageInfo, $pageContents)
    {
        foreach ($pageContents as $pageContent) {
            $objects = $this->renderSinglePage($pageInfo, $pageContent);
            foreach ($objects as $object) {
                yield $object;
            }
        }
    }

    private function renderSinglePage($pageInfo, $pageContent)
    {
        // PDF Page <n>
        $pageObj = $this->pdfBuilder->createPage(
            $this->pageCollectionObj
        );
        $arraySize = $this->createArraySize($pageInfo, $this->defaultPageInfo);
        if ($arraySize != null) {
            $this->pdfBuilder->registerMediaBox($pageObj, $arraySize);
        }
        $this->pdfBuilder->registerPage($this->pageCollectionObj, $pageObj);
        yield $pageObj;

        // PDF Page <n> content
        $pageContentObj = $this->pdfBuilder->createPageContent();
        $this->writeContentStream($pageContentObj, $pageContent);
        $this->pdfBuilder->registerPageContent($pageObj, $pageContentObj);
        yield $pageContentObj;
    }

    public function createArraySize($pageInfo, $previousPageInfo = null)
    {
        $orientation = $pageInfo->orientation;
        $size = $pageInfo->size;

        if ($previousPageInfo != null
            && $previousPageInfo->orientation == $orientation
            && $previousPageInfo->size == $size) {
                return null;
        }

        $unitInfo = $size->unitInfo;
        $width = $unitInfo->toInch($size->width) *72;
        $height = $unitInfo->toInch($size->height) *72;

        if ($orientation->isLandscape()) {
            $pageSizeArray = $this->pdfBuilder->createMediaBox($width, $height);
        } else {
            $pageSizeArray = $this->pdfBuilder->createMediaBox($height, $width);
        }
        return $pageSizeArray;
    }

    private function writeContentStream($pageContentObj, $content)
    {
        $contentStream = \gzcompress($content);
        $this->pdfBuilder->setStreamContent($pageContentObj, $contentStream);
    }
}
