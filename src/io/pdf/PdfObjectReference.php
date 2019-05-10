<?php namespace insma\otuspdf\io\pdf;

class PdfObjectReference extends \insma\otuspdf\base\BaseObject
{
    private $id;
    private $version;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function setObject($object)
    {
        $this->id = $object->id;
        $this->version = $object->version;
    }

    public function toString()
    {
        $content = "{$this->id} {$this->version} R";
        return $content;
    }
}
