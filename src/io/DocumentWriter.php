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
namespace insma\otuspdf\io;

use insma\otuspdf\base\InvalidCallException;
use insma\otuspdf\io\pdf\PdfArray;
use insma\otuspdf\io\pdf\PdfCrossReference;
use insma\otuspdf\io\pdf\PdfDictionary;
use insma\otuspdf\io\pdf\PdfName;
use insma\otuspdf\io\pdf\PdfNumber;
use insma\otuspdf\io\pdf\PdfObject;
use insma\otuspdf\io\pdf\PdfObjectFactory;
use insma\otuspdf\io\pdf\PdfObjectReference;
use insma\otuspdf\io\pdf\PdfStream;
use insma\otuspdf\io\pdf\PdfString;
use insma\otuspdf\io\pdf\PdfTrailer;
use insma\otuspdf\meta\PageOrientationInfo;
use insma\otuspdf\meta\PageSizeInfo;
use insma\otuspdf\meta\PositionInfo;

class DocumentWriter extends \insma\otuspdf\base\BaseObject
{
    private $document;
    private $objectFactory;
    private $crossReference;
    private $trailer;
    private $offset;
    private $content;

    public function __construct(\insma\otuspdf\Document $document)
    {
        $this->document = $document;
        $this->objectFactory = new PdfObjectFactory();
        $this->crossReference = new PdfCrossReference();
        $this->trailer = new PdfTrailer();
        $this->offset = 0;
        $this->content = '';
    }

    private function generatePdfContent()
    {
        $objects = [];

        // PDF Catalog
        $catalogObj = $this->objectFactory->create();
        $catalogObj->content = new PdfDictionary();
        $catalogObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Catalog'])
        );
        $objects[] = $catalogObj;

        // PDF Outlines
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
        $objects[] = $outlinesObj;
        $catalogObj->content->addItem(
            new PdfName(['value' => 'Outlines']),
            new PdfObjectReference(['object' => $outlinesObj])
        );

        // PDF Pages collection
        if (count($this->document->pages) === 0) {
            throw new InvalidCallException('Document does not contain pages.');
        }

        $resourcesDict = new PdfDictionary();
        $pageWriter = new PageRender($this->objectFactory, $resourcesDict);
        $objects[] = $pageWriter->renderPageCollection($this->document->pages, $catalogObj);

        // PDF Proc Set
        $procSetObj = $this->objectFactory->create();
        $procSetObj->content = new PdfArray();
        $procSetObj->content->addItem(new PdfName(['value' => 'PDF']));
        $objects[] = $procSetObj;
        $resourcesDict->addItem(
            new PdfName(['value' => 'ProcSet']),
            new PdfObjectReference(['object' => $procSetObj])
        );

        $docInfoObject = null;
        if (!empty($this->document->info)) {
            $infoDict = new PdfDictionary();
            $this->setInfoTextIfNotEmpty($infoDict, 'title', 'Title');
            $this->setInfoTextIfNotEmpty($infoDict, 'author', 'Author');
            $this->setInfoTextIfNotEmpty($infoDict, 'subject', 'Subject');
            $this->setInfoTextIfNotEmpty($infoDict, 'keywords', 'Keywords');
            $this->setInfoTextIfNotEmpty($infoDict, 'creator', 'Creator');
            $this->setInfoTextIfNotEmpty($infoDict, 'producer', 'Producer');
            $this->setInfoTextIfNotEmpty($infoDict, 'creationDate', 'CreationDate');
            $this->setInfoTextIfNotEmpty($infoDict, 'modificationDate', 'ModDate');
            if (!empty($infoDict->items)) {
                $docInfoObject = $this->objectFactory->create();
                $docInfoObject->content = $infoDict;
                $objects[] = $docInfoObject;
            }
        }

        foreach ($this->document->pages as $n => $page) {
            $pageObjects = $pageWriter->renderPage($page);
            $objects = array_merge($objects, $pageObjects);
        }

        // Simple font F1
        $fontObj1 = $this->objectFactory->create();
        $fontObj1->content = new PdfDictionary();
        $fontObj1->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Font'])
        );
        $fontObj1->content->addItem(
            new PdfName(['value' => 'Subtype']),
            new PdfName(['value' => 'Type1'])
        );
        $fontObj1->content->addItem(
            new PdfName(['value' => 'Name']),
            new PdfName(['value' => 'F1'])
        );
        $fontObj1->content->addItem(
            new PdfName(['value' => 'BaseFont']),
            new PdfName(['value' => 'Times-Roman'])
        );
        $fontObj1->content->addItem(
            new PdfName(['value' => 'Encoding']),
            new PdfName(['value' => 'WinAnsiEncoding'])
        );
        $objects[] = $fontObj1;
        $fontsDict = new PdfDictionary();
        $fontsDict->addItem(
            new PdfName(['value' => 'F1']),
            new PdfObjectReference(['object' => $fontObj1])
        );
        $resourcesDict->addItem(
            new PdfName(['value' => 'Font']),
            $fontsDict
        );

        $this->writeHeader();
        $this->writeBody($objects);
        $this->writeCrossReference();
        $this->writeTrailer($catalogObj, $docInfoObject);
    }

    private function setInfoTextIfNotEmpty($dictionary, $property, $pdfKey)
    {
        if (!empty($this->document->info->$property)) {
            $dictionary->addItem(
                new PdfName(['value' => $pdfKey]),
                new PdfString(['value' => $this->document->info->$property])
            );
        }
    }

    public function save(String $filepath)
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
        echo($encodedContent);
    }

    private function writeHeader()
    {
        $this->writeLine('%PDF-1.7');
    }

    private function writeBody($objects)
    {
        $this->offset = \strlen($this->content);
        foreach ($objects as $object) {
            $text = $object->toString();
            $this->crossReference->registerObject($object, $this->offset);
            $this->writeLine($text);
            $this->offset += \strlen($text);
        }
    }

    private function writeCrossReference()
    {
        $this->trailer->xrefOffset = $this->offset;
        $this->writeLine($this->crossReference->toString());
    }

    private function writeTrailer($rootObject, $docInfoObject = null)
    {
        $this->trailer->content->addItem(
            new PdfName(['value' => 'Size']),
            new PdfNumber(['value' => $this->crossReference->size])
        );
        $this->trailer->content->addItem(
            new PdfName(['value' => 'Root']),
            new PdfObjectReference(['object' => $rootObject])
        );
        if ($docInfoObject instanceof PdfObject) {
            $this->trailer->content->addItem(
                new PdfName(['value' => 'Info']),
                new PdfObjectReference(['object' => $docInfoObject])
            );
        }

        $this->writeLine($this->trailer->toString());
        $this->writeLine('%%EOF');
    }

    private function writeLine($line)
    {
        $this->content .= $line. "\n";
    }

    private function encodeContent($data)
    {
        return $data;
    }
}
