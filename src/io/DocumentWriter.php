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
        $defaultOrientation = PageOrientationInfo::getPortrait();
        $defaultSize = PageSizeInfo::getA4();
        $defaultArraySize = $this->createArraySize($defaultOrientation, $defaultSize);

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
            list($orientation, $size, $isDefined) = $this->findOrientationAndSize($page->info, $defaultOrientation, $defaultSize);
            if ($isDefined) {
                $arraySize = $this->createArraySize($orientation, $size);
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
            $this->writeContentStream($page, $pageContentObj, $orientation, $size);
            $objects[] = $pageContentObj;
            $pageObj->content->addItem(
                new PdfName(['value' => 'Contents']),
                new PdfObjectReference(['object' => $pageContentObj])
            );
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
            new PdfObjectReference(['object' => $fontObj1]),
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

    private function createArraySize($orientation, $size)
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

    private function computeTextStartPosition($pageOrientation, $pageSize)
    {
        if ($pageOrientation->isLandscape()) {
            return new PositionInfo(0.0, $pageSize->height *72);
        } else {
            return new PositionInfo(0.0, $pageSize->width *72);
        }
    }

    private function encodeContent($data)
    {
        return $data;
    }

    private function findOrientationAndSize($pageInfo, $defautOrientation, $defaultSize)
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
        $fontSize = 14;
        $startPosition = $this->computeTextStartPosition($pageOrientation, $pageSize);
        $x = intval($startPosition->x);
        $y = intval($startPosition->y -$fontSize);

        $content = "BT\n";
        $content .= "\t /F1 {$fontSize} Tf\n";
        $content .= "\t {$fontSize} TL\n";
        $content .= "\t {$x} {$y} Td\n";
        foreach ($page->items as $text) {
            $lines = \explode("\n", $text->text);
            foreach ($lines as $textLine) {
                $content .= "\t ({$textLine}) Tj\n";
                $content .= "\t T*\n";
            }
        }
        $content .= "ET";

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
