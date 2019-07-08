<?php namespace trogon\otuspdf\io;

use \trogon\otuspdf\Document;
use \trogon\otuspdf\Text;

class DocumentParserXmlFlow extends \trogon\otuspdf\base\BaseObject
{
    public function processDocument(string $xmlContent)
    {
        $reader = new \XMLReader();
        if ($reader->xml($xmlContent)) {
            $document = new Document();
            $this->processXml($reader, $document);
            return $document;
        }
        return null;
    }

    public function processXml(\XMLReader $reader, Document $document)
    {
        if ($reader->read()) {
            if ($reader->name == 'FlowDocument') {
                $document->pages->add();
                $this->processFlowDocument($reader, $document);
            }
        }
    }

    private function processFlowDocument(\XMLReader $reader, Document $document)
    {
        if ($reader->read()) {
            do {
                switch ($reader->name) {
                    case 'List':
                        $this->processList($reader, $document);
                        break;
                    case 'Paragraph':
                        $this->processParagraph($reader, $document);
                        break;
                    case 'Section':
                        $this->processSection($reader, $document);
                        break;
                    case 'Table':
                        $this->processTable($reader, $document);
                        break;
                }
            } while($reader->next() && $reader->name != 'FlowDocument');
        }
    }

    private function processList(\XMLReader $reader, Document $document)
    {
        if ($reader->read()) {
            do {
                switch ($reader->name) {
                    case 'ListItem':
                        break;
                }
            } while($reader->next() && $reader->name != 'List');
        }
    }

    private function processListItem(\XMLReader $reader, Document $document)
    {

    }

    private function processParagraph(\XMLReader $reader, Document $document)
    {

    }

    private function processSection(\XMLReader $reader, Document $document)
    {

    }

    private function processTable(\XMLReader $reader, Document $document)
    {

    }
}
