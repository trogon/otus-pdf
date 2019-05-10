<?php namespace insma\otuspdf\io\pdf;

class PdfObject extends \insma\otuspdf\base\BaseObject
{
    private $id;
    private $version;
    private $content;
    private $streamObject;

    public function __construct($id, $version = 0)
    {
        $this->id = $id;
        $this->version = $version;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getStream()
    {
        return $this->streamObject;
    }

    public function setStream($stream)
    {
        $this->streamObject = $stream;
    }

    public function toString()
    {
        $content = "{$this->id} {$this->version} obj\n";
        $content .= $this->content->toString() . "\n";
        if (!empty($this->streamObject)) {
            $content .= $this->streamObject->toString() . "\n";
        }
        $content .= "endobj";
        return $content;
    }
}
