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
        $defaultOrientation = PageOrientationInfo::getPortrait();
        $defaultSize = PageSizeInfo::getA4();
        $defaultArraySize = $this->createArraySize($defaultOrientation, $defaultSize, null, null);

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

        $pageCollectionObj = $this->objectFactory->create();
        $pageCollectionObj->content = new PdfDictionary();
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Pages'])
        );
        $pageRefs = new PdfArray();
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Kids']),
            $pageRefs
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Count']),
            new PdfNumber(['value' => \count($this->document->pages)])
        );
        $resourcesDict = new PdfDictionary();
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Resources']),
            $resourcesDict
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'MediaBox']),
            $defaultArraySize
        );
        $objects[] = $pageCollectionObj;
        $catalogObj->content->addItem(
            new PdfName(['value' => 'Pages']),
            new PdfObjectReference(['object' => $pageCollectionObj])
        );

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
            // PDF Page <n>
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
            $arraySize = $this->createArraySize($page->info->orientation, $page->info->size, $defaultOrientation, $defaultSize);
            if (!empty($arraySize)) {
                $pageObj->content->addItem(
                    new PdfName(['value' => 'MediaBox']),
                    $arraySize
                );
            }
            $objects[] = $pageObj;
            $pageRefs->addItem(new PdfObjectReference(['object' => $pageObj]));

            // PDF Page <n> content
            $pageContentObj = $this->objectFactory->create();
            $pageContentObj->content = new PdfDictionary();
            $pageContentObj->stream = new PdfStream();
            $pageContentObj->stream->value = '';
            $pageContentObj->content->addItem(
                new PdfName(['value' => 'Length']),
                new PdfNumber(['value' => $pageContentObj->stream->length])
            );
            $objects[] = $pageContentObj;
            $pageObj->content->addItem(
                new PdfName(['value' => 'Contents']),
                new PdfObjectReference(['object' => $pageContentObj])
            );
        }

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

    private function createArraySize($orientation, $size, $defautOrientation, $defaultSize)
    {
        $isOrientationDefined = ($orientation instanceof PageOrientationInfo);
        $isSizeDefined = ($size instanceof PageSizeInfo);

        if (($isOrientationDefined && $orientation != $defautOrientation)
         || ($size && $orientation != $defaultSize)) {
            $orientation = $isOrientationDefined ? $orientation: $defautOrientation;
            $size = $isSizeDefined ? $size : $defaultSize;

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
        return null;
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
        $encodedData = \iconv(\mb_detect_encoding($data), 'ISO-8859-1//TRANSLIT', $data);
        if ($encodedData === false) {
            throw new \Exception('Convesion failed');
        }
        return $encodedData;
    }
}
