<?php namespace insma\otuspdf\io\pdf;

use insma\otuspdf\io\pdf\PdfObject;

class PdfCrossReference extends \insma\otuspdf\base\BaseObject
{
    private $objects = [];
    private $objectLocations = [];
    private $refBlocks = [0];

    public function getSize()
    {
        $keys = \array_keys($this->refBlocks);
        $lastKey = end($keys);
        return $lastKey + $this->refBlocks[$lastKey];
    }

    public function registerObject(PdfObject $pdfObject, int $offset)
    {
        $this->objects[$pdfObject->id] = $pdfObject;
        $this->objectLocations[$pdfObject->id] = $offset;
        $this->computeRefBlocks();
    }

    private function computeRefBlocks()
    {
        $blocks = [];
        $blockSize = 0;
        $currentBlock = 0;
        $currentObjectId = -1;
        foreach ($this->objects as $id => $pdfObject) {
            if ($id != $currentObjectId) {
                $blocks[$currentBlock] = $blockSize;
                $blockSize = 1;
                $currentObjectId = $id;
                $currentBlock = $id;
            } else {
                $blockSize++;
            }
            $currentObjectId++;
        }
        $blocks[$currentBlock] = $blockSize;
        $this->refBlocks = $blocks;
    }

    public function toString()
    {
        $content = "xref";
        $currentObjectId = -1;
        foreach ($this->objects as $id => $pdfObject) {
            if ($id != $currentObjectId) {
                $currentObjectId = $id;
                $content .= "\n{$id} {$this->refBlocks[$id]}";
            }
            if ($pdfObject instanceof PdfObject) {
                $offset = $this->objectLocations[$id];
                $version = $pdfObject->version;
                $content .= "\n" . substr('00000000' . $offset, -8) . ' ' . substr('00000' . $version, -5) . " n ";
            } else {
                $content .= "\n00000000 65535 f ";
            }
            $currentObjectId++;
        }
        return $content;
    }
}
