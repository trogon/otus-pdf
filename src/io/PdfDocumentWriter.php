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

use ArrayIterator;

use trogon\otuspdf\base\InvalidOperationException;
use trogon\otuspdf\io\BlockRender;
use trogon\otuspdf\io\FontRender;
use trogon\otuspdf\io\PdfBuilder;
use trogon\otuspdf\io\TextRender;

class PdfDocumentWriter extends \trogon\otuspdf\base\DependencyObject
{
    private $document;
    private $pdfBuilder;
    private $content;

    public function __construct(\trogon\otuspdf\Document $document)
    {
        $this->document = $document;
        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $this->pdfBuilder = new PdfBuilder();
        $this->content = '';
    }

    private function generatePdfContent()
    {
        $pdfBuilder = $this->pdfBuilder;
        $baseObjects = new ArrayIterator();

        // PDF Null Object
        $nullObj = $pdfBuilder->createNullObject();
        $baseObjects->append($nullObj);

        // PDF catalog
        $catalogObj = $pdfBuilder->createCatalog();
        $baseObjects->append($catalogObj);

        // PDF outlines
        $outlinesObj = $pdfBuilder->createOutlines();
        $pdfBuilder->registerOutlines($catalogObj, $outlinesObj);
        $baseObjects->append($outlinesObj);

        // Check if there are any pages
        if (count($this->document->pages) === 0) {
            throw new InvalidOperationException('Document does not contain pages.');
        }

        // PDF pages collection
        $resourcesDict = $pdfBuilder->createResourceCatalog();
        $pageWriter = new PageRender($this->pdfBuilder, $resourcesDict);
        $baseObjects->append(
            $pageWriter->renderPageCollection($this->document->pages, $catalogObj)
        );

        // PDF proc set
        $procSetObj = $pdfBuilder->createProcSet();
        $pdfBuilder->registerProcSetResource($resourcesDict, $procSetObj);
        $baseObjects->append($procSetObj);

        // PDF document information
        $docInfoObject = null;
        if (!empty($this->document->info)) {
            $docInfoObject = $pdfBuilder->createDocumentInfo([
                'Title' => $this->document->info->title,
                'Author' => $this->document->info->author,
                'Subject' => $this->document->info->subject,
                'Keywords' => $this->document->info->keywords,
                'Creator' => $this->document->info->creator,
                'Producer' => $this->document->info->producer,
                'CreationDate' => $this->document->info->creationDate,
                'ModDate' => $this->document->info->modificationDate,
                'Title' => $this->document->info->title,
            ]);
            if ($docInfoObject != null) {
                $baseObjects->append($docInfoObject);
            }
        }

        $objects = iterator_to_array($baseObjects, false);
        // PDF fonts render
        $pdfFontRender = new FontRender($pdfBuilder);
        // PDF text render
        $pdfBlockRender = new BlockRender($pdfFontRender);

        // PDF pages
        foreach ($this->document->pages as $n => $page) {
            $pageInfo = $pageWriter->getPageInfo($page);
            $contents = $pdfBlockRender->renderBlocks($page->blocks, $pageInfo);
            $pageObjects = $pageWriter->renderPage($pageInfo, $contents);
            $objects = array_merge($objects, iterator_to_array($pageObjects, false));
        }

        // PDF fonts catalog
        $pdfFontRender->registerFontsResource($resourcesDict);
        $fontObjects = $pdfFontRender->createFontObjects();
        $objects = array_merge($objects, iterator_to_array($fontObjects, false));

        // Transform PDF objects into PDF text
        $this->writeBegin();
        $crossReference = $pdfBuilder->createCrossReference();
        $xrefOffset = $this->writeBody($objects, $crossReference);
        $this->writeObject($crossReference);
        $trailer = $pdfBuilder->createTrailer(
            $catalogObj,
            $xrefOffset,
            $crossReference->size,
            $docInfoObject
        );
        $this->writeObject($trailer);
        $this->writeEnd();
    }

    public function save($filepath)
    {
        if (empty($this->content)) {
            $this->generatePdfContent();
        }

        $fp = fopen($filepath, 'w');
        $encodedContent = $this->encodeContent($this->content);
        fwrite($fp, $encodedContent);
        fclose($fp);
    }

    public function toString()
    {
        if (empty($this->content)) {
            $this->generatePdfContent();
        }

        $encodedContent = $this->encodeContent($this->content);
        return $encodedContent;
    }

    private function write($text)
    {
        $this->content .= $text;
        return \strlen($text);
    }

    private function writeBegin()
    {
        $this->writeLine('%PDF-1.7');
    }

    private function writeBody($objects, $crossReference)
    {
        $offset = \strlen($this->content);
        foreach ($objects as $object) {
            if ($object->isNull) {
                $crossReference->registerObject($object, $offset);
            } else {
                $text = $object->toString();
                $crossReference->registerObject($object, $offset);
                $offset += $this->writeLine($text);
            }
        }
        return $offset;
    }

    private function writeObject($object)
    {
        $this->writeLine($object->toString());
    }

    private function writeEnd()
    {
        $this->write('%%EOF');
    }

    private function writeLine($line)
    {
        $line .= "\n";
        return $this->write($line);
    }

    private function encodeContent($data)
    {
        return $data;
    }
}
