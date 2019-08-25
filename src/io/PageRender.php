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
use trogon\otuspdf\io\pdf\PdfDictionary;
use trogon\otuspdf\meta\PaddingInfo;
use trogon\otuspdf\meta\PageInfo;
use trogon\otuspdf\meta\PageOrientationInfo;
use trogon\otuspdf\meta\PageSizeInfo;

class PageRender extends \trogon\otuspdf\base\DependencyObject
{
    private $defaultPageInfo;
    private $pageCollectionObj;
    private $builder;
    private $resourceCatalog;

    public function __construct(PdfBuilder $builder, PdfDictionary $resourceCatalog)
    {
        $this->builder = $builder;
        $this->resourceCatalog = $resourceCatalog;
        parent::__construct();
    }

    public function init()
    {
        parent::init();
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
            $this->builder->createPageCollection($this->resourceCatalog, $defaultArraySize);
        $this->builder->registerPageCollection($catalogObj, $this->pageCollectionObj);
        return $this->pageCollectionObj;
    }

    public function renderPage($pageInfo, $pageContents)
    {
        $isEmpty = true;
        foreach ($pageContents as $pageContent) {
            $objects = $this->renderSinglePage($pageInfo, $pageContent);
            foreach ($objects as $object) {
                yield $object;
                $isEmpty = false;
            }
        }
        if ($isEmpty) {
            $emptyContent = '';
            $objects = $this->renderSinglePage($pageInfo, $emptyContent);
            foreach ($objects as $object) {
                yield $object;
            }  
        }
    }

    private function renderSinglePage($pageInfo, $pageContent)
    {
        // PDF Page <n>
        $pageObj = $this->builder->createPage(
            $this->pageCollectionObj
        );
        $arraySize = $this->createArraySize($pageInfo, $this->defaultPageInfo);
        if ($arraySize != null) {
            $this->builder->setCropBox($pageObj, $arraySize);
            $this->builder->setMediaBox($pageObj, $arraySize);
        }
        $this->builder->registerPage($this->pageCollectionObj, $pageObj);
        yield $pageObj;

        // PDF Page <n> content
        $pageContentObj = $this->builder->createPageContent();
        $this->writeContentStream($pageContentObj, $pageContent);
        $this->builder->registerPageContent($pageObj, $pageContentObj);
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
            $pageSizeArray = $this->builder->createMediaBox($width, $height);
        } else {
            $pageSizeArray = $this->builder->createMediaBox($height, $width);
        }
        return $pageSizeArray;
    }

    private function writeContentStream($pageContentObj, $content)
    {
        $contentStream = \gzcompress($content);
        $stream = $this->builder->createStreamContent($contentStream);
        $this->builder->setStream($pageContentObj, $stream);
    }
}
